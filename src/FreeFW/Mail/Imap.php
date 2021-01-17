<?php
namespace FreeFW\Mail;

/**
 * Gestion IMAP
 *
 * @author jeromeklam
 * @package Mail
 */
class Imap
{
    /**
     * Timeout
     * @var number
     */
    const TIMEOUT = 30;

    /**
     * Pas de sujet
     * @var string
     */
    const NO_SUBJECT = '(no subject)';

    /**
     * Serveur
     * @var string
     */
    protected $host = null;

    /**
     * Port
     * @var string
     */
    protected $port = null;

    /**
     * ssl
     * @var boolean
     */
    protected $ssl = false;

    /**
     * tls
     * @var boolean
     */
    protected $tls = false;

    /**
     * Utilisateur
     * @var string
     */
    protected $username = null;

    /**
     * Mot de passe
     * @var string
     */
    protected $password = null;

    /**
     * Tag
     * @var number
     */
    protected $tag = 0;

    /**
     * Total
     * @var number
     */
    protected $total = 0;

    /**
     * Suivant
     * @var number
     */
    protected $next = 0;

    /**
     * Buffer
     * @var string
     */
    protected $buffer = null;

    /**
     * Resource
     * @var resource
     */
    protected $socket = null;

    /**
     * Mailbox
     * @var string
     */
    protected $mailbox = null;

    /**
     * Mailboxes
     * @var array
     */
    protected $mailboxes = array();

    /**
     * Debugging
     * @var boolean
     */
    private $debugging = false;

    /**
     * Instance
     * @var \FreeFW\Mail\Imap
     */
    protected static $instance = null;

    /**
     * Config
     * @var array
     */
    protected $config = null;

    /**
     * Constructeur
     *
     * @param array $p_config
     */
    protected function __construct($p_config = array())
    {
        $this->config = $p_config;
        $this->host   = $this->config['server'];
        $this->port   = $this->config['incoming'];
        if (array_key_exists('secure', $this->config) && $this->config['secure'] != '') {
            if ($this->config['secure'] == true) {
                $this->ssl = true;
                $this->tls = false;
            } else {
                if ($this->config['secure'] == 'tls') {
                    $this->ssl = false;
                    $this->tls = true;
                } else {
                    $this->ssl = true;
                    $this->tls = false;
                }
            }
            $this->username = $this->config['username'];
            $this->password = $this->config['password'];
        }
    }

    /**
     * Retourne une instance
     *
     * @param array $p_config
     *
     * @return \FreeFW\Mail\Imap
     */
    public static function getInstance($p_config = null)
    {
        if (self::$instance === null) {
            self::$instance = new self($p_config);
        }
        return self::$instance;
    }

    /**
     * Connexion
     *
     * @param number  $p_timeout
     * @param boolean $test
     *
     * @return \static
     */
    public function connect($p_timeout = self::TIMEOUT, $p_test = false)
    {
        if ($this->socket) {
            return $this;
        }
        $host = $this->host;
        // Forcé car faut résoudre le pb des certificats ssl...
        // Port 143 en non sécurisé... 993 sinon
        $this->ssl = false;
        if ($this->ssl) {
            $host = 'ssl://' . $host;
        }
        $errno  =  0;
        $errstr = '';
        $this->socket = fsockopen($host, $this->port, $errno, $errstr, $p_timeout);
        if (!$this->socket) {
            throw new \Exception(sprintf('Erreur de connexion 1 %s:%s !', $host, $this->port));
        }
        if (strpos($this->getLine(), '* OK') === false) {
            $this->disconnect();
            throw new \Exception(sprintf('Erreur de connexion 2 %s:%s !', $host, $this->port));
        }
        if ($this->tls) {
            $this->send('STARTTLS');
            if (!stream_socket_enable_crypto($this->socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                $this->disconnect();
                throw new \Exception(sprintf('Erreur TLS %s', $this->port));
            }
        }
        if ($p_test) {
            fclose($this->socket);
            $this->socket = null;
            return $this;
        }
        $result = $this->call('LOGIN', $this->escape($this->username, $this->password));
        if (!is_array($result) || strpos(implode(' ', $result), 'OK') === false) {
            $this->disconnect();
            throw new \Exception(sptrintf('Erreur de login'));
        }
        return $this;
    }

    /**
     * Disconnects from the server
     *
     * @return \FreeFW\Mail\Imap
     */
    public function disconnect()
    {
        if ($this->socket) {
            $this->send('LOGOUT');
            fclose($this->socket);
            $this->socket = null;
        }
        return $this;
    }

    /**
     * Returns the active mailbox
     *
     * @return string
     */
    public function getActiveMailbox()
    {
        return $this->mailbox;
    }

    /**
     * Retourne une liste d'emails
     *
     * @param number $p_start Pagination start
     * @param number $p_range Pagination range
     * @param bool   $p_body  add body to threads
     *
     * @return array
     */
    public function getEmails($p_start = 0, $p_range = 10, $p_body = false)
    {
        if (!$this->socket) {
            $this->connect();
        }
        if ($this->total == 0) {
            return array();
        }
        if (is_array($p_start)) {
            $set = implode(',', $p_start);
        } else {
            $p_range = $p_range > 0 ? $p_range : 1;
            $p_start = $p_start >= 0 ? $p_start : 0;
            $max     = $this->total - $p_start;
            if ($max < 1) {
                $max = $this->total;
            }
            $min = $max - $p_range + 1;
            if ($min < 1) {
                $min = 1;
            }
            $set = $min . ':' . $max;
            if ($min == $max) {
                $set = $min;
            }
        }
        $items = array('UID', 'FLAGS', 'BODY[HEADER]');
        if ($p_body) {
            $items  = array('UID', 'FLAGS', 'BODY[]');
        }
        $emails = $this->getEmailResponse('FETCH', array($set, $this->getList($items)));
        $emails = array_reverse($emails);
        return $emails;
    }

    /**
     * Retuourne le numbre total
     *
     * @return number
     */
    public function getEmailTotal()
    {
        return $this->total;
    }

    /**
     * Retourne le nombre total
     *
     * @return number
     */
    public function getNextUid()
    {
        return $this->next;
    }

    /**
     * Retourne les mailboxes
     *
     * @return array
     */
    public function getMailboxes()
    {
        if (!$this->socket) {
            $this->connect();
        }
        $response = $this->call('LIST', $this->escape('', '*'));
        $mailboxes = array();
        foreach ($response as $line) {
            if (strpos($line, 'Noselect') !== false || strpos($line, 'LIST') == false) {
                continue;
            }
            $line = explode('"', $line);
            if (strpos(trim($line[0]), '*') !== 0) {
                continue;
            }
            $mailboxes[] = $line[count($line)-2];
        }
        return $mailboxes;
    }

    /**
     * Retourne une liste d'emails uniques
     *
     * @param number  $p_uid
     * @param boolean $p_body
     *
     * @return array
     */
    public function getUniqueEmails($p_uid, $p_body = false)
    {
        if (!$this->socket) {
            $this->connect();
        }
        if ($this->total == 0) {
            return array();
        }
        if (is_array($p_uid)) {
            $p_uid = implode(',', $p_uid);
        }
        $items = array('UID', 'FLAGS', 'BODY[HEADER]');
        if ($p_body) {
            $items = array('UID', 'FLAGS', 'BODY[]');
        }
        $p_first = is_numeric($p_uid) ? true : false;
        return $this->getEmailResponse('UID FETCH', array($p_uid, $this->getList($items)), $p_first);
    }

    /**
     * Déplace un email
     *
     * @param number $p_uid
     * @param string $p_mailbox
     *
     * @return \static
     */
    public function move($p_uid, $p_mailbox)
    {
        if (!$this->socket) {
            $this->connect();
        }
        $this->call('UID COPY '.$p_uid.' '.$p_mailbox);
        return $this->remove($p_uid);
    }

    /**
     * Supprime
     *
     * @param number $p_uid
     *
     * @return \static
     */
    public function remove($p_uid)
    {
        if (!$this->socket) {
            $this->connect();
        }
        $this->call('UID STORE '.$p_uid.' FLAGS.SILENT \Deleted');
        return $this;
    }

    /**
     * Supprime
     *
     * @return \FreeFW\Mail\Imap
     */
    public function expunge()
    {
        $this->call('expunge');
        return $this;
    }

    /**
     * Recherche
     *
     * @param array  $p_filter
     * @param number $p_start
     * @param number $p_range
     * @param bool   $p_or
     * @param bool   $p_body
     *
     * @return array
     */
    public function search(array $p_filter, $p_start = 0, $p_range = 10, $p_or = false, $p_body = false)
    {
        if (!$this->socket) {
            $this->connect();
        }
        $search = $not = array();
        foreach ($p_filter as $where) {
            if (is_string($where)) {
                $search[] = $where;
                continue;
            }
            if ($where[0] == 'NOT') {
                $not = $where[1];
                continue;
            }
            $item = $where[0].' "'.$where[1].'"';
            if (isset($where[2])) {
                $item .= ' "'.$where[2].'"';
            }
            $search[] = $item;
        }
        if ($p_or && count($search) > 1) {
            $query = null;
            while ($item = array_pop($search)) {
                if (is_null($query)) {
                    $query = $item;
                } else {
                    if (strpos($query, 'OR') !== 0) {
                        $query = 'OR ('.$query.') ('.$item.')';
                    } else {
                        $query = 'OR ('.$item.') ('.$query.')';
                    }
                }
            }
            $search = $query;
        } else {
            $search = implode(' ', $search);
        }
        $response = $this->call('UID SEARCH '.$search);
        $result   = array_pop($response);
        if (strpos($result, 'OK') !== false) {
            $uids = explode(' ', $response[0]);
            array_shift($uids);
            array_shift($uids);
            foreach ($uids as $i => $p_uid) {
                if (in_array($p_uid, $not)) {
                    unset($uids[$i]);
                }
            }
            if (empty($uids)) {
                return array();
            }
            $uids = array_reverse($uids);
            $count  = 0;
            foreach ($uids as $i => $id) {
                if ($i < $p_start) {
                    unset($uids[$i]);
                    continue;
                }
                $count ++;
                if ($p_range != 0 && $count > $p_range) {
                    unset($uids[$i]);
                    continue;
                }
            }
            return $this->getUniqueEmails($uids, $p_body);
        }
        return array();
    }

    /**
     * Recherche du nombre total de mails
     *
     * @param array $p_filter
     * @param bool  $p_or
     *
     * @return number
     */
    public function searchTotal(array $p_filter, $p_or = false)
    {
        if (!$this->socket) {
            $this->connect();
        }
        $search = array();
        foreach ($p_filter as $where) {
            $item = $where[0].' "'.$where[1].'"';
            if (isset($where[2])) {
                $item .= ' "'.$where[2].'"';
            }
            $search[] = $item;
        }
        if ($p_or) {
            $search = 'OR ('.implode(') (', $search).')';
        } else {
            $search = implode(' ', $search);
        }
        $response = $this->call('UID SEARCH '.$search);
        $result   = array_pop($response);
        if (strpos($result, 'OK') !== false) {
            $uids = explode(' ', $response[0]);
            array_shift($uids);
            array_shift($uids);
            return count($uids);
        }
        return 0;
    }

    /**
     * Affectation de la boite de dialogue active
     *
     * @param string $p_mailbox
     *
     * @return \static|false
     */
    public function setActiveMailbox($p_mailbox)
    {
        if (!$this->socket) {
            $this->connect();
        }
        $response = $this->call('SELECT', $this->escape($p_mailbox));
        $result   = array_pop($response);
        foreach ($response as $line) {
            if (strpos($line, 'EXISTS') !== false) {
                list($star, $this->total, $type) = explode(' ', $line, 3);
            } else {
                if (strpos($line, 'UIDNEXT') !== false) {
                    list($star, $ok, $next, $this->next, $type) = explode(' ', $line, 5);
                    $this->next = substr($this->next, 0, -1);
                }
            }
            if ($this->total && $this->next) {
                break;
            }
        }
        if (strpos($result, 'OK') !== false) {
            $this->mailbox = $p_mailbox;
            return $this;
        }

        return false;
    }

    /**
     * Call
     *
     * @param string $p_command
     * @param array  $p_parameter
     *
     * @return string|false
     */
    protected function call($p_command, $p_parameters = array())
    {
        if (!$this->send($p_command, $p_parameters)) {
            return false;
        }
        return $this->receive($this->tag);
    }

    /**
     * Réponse ligne à ligne
     *
     * @return string
     */
    protected function getLine()
    {
        $line = fgets($this->socket);
        if ($line === false) {
            $this->disconnect();
        }
        $this->debug('Receiving: '.$line);
        return $line;
    }

    /**
     * Réception
     *
     * @param string $p_sentTag
     *
     * @return string
     */
    protected function receive($p_sentTag)
    {
        $this->buffer = array();
        $start        = time();
        while (time() < ($start + self::TIMEOUT)) {
            list($receivedTag, $line) = explode(' ', $this->getLine(), 2);
            $this->buffer[] = trim($receivedTag . ' ' . $line);
            if ($receivedTag == 'TAG'.$p_sentTag) {
                return $this->buffer;
            }
        }
        return null;
    }

    /**
     * Envoi
     *
     * @param string $p_command
     * @param array  $p_parameter
     *
     * @return boolean
     */
    protected function send($p_command, $p_parameters = array())
    {
        $this->tag ++;
        $line = 'TAG' . $this->tag . ' ' . $p_command;
        if (!is_array($p_parameters)) {
            $p_parameters = array($p_parameters);
        }
        foreach ($p_parameters as $parameter) {
            if (is_array($parameter)) {
                if (fputs($this->socket, $line . ' ' . $parameter[0] . "\r\n") === false) {
                    return false;
                }
                if (strpos($this->getLine(), '+ ') === false) {
                    return false;
                }
                $line = $parameter[1];
            } else {
                $line .= ' ' . $parameter;
            }
        }
        $this->debug('Sending: '.$line);
        return fputs($this->socket, $line . "\r\n");
    }

    /**
     * Debug
     *
     * @param string $p_string
     *
     * @return \static
     */
    private function debug($p_string)
    {
        if ($this->debugging) {
            $p_string = htmlspecialchars($p_string);
            echo '<pre>'.$p_string.'</pre>'."\n";
        }
        return $this;
    }

    /**
     * Escape spécifique IMAP
     *
     * @param string $p_string
     *
     * @return string
     */
    private function escape($p_string)
    {
        if (func_num_args() < 2) {
            if (strpos($p_string, "\n") !== false) {
                return array('{' . strlen($p_string) . '}', $p_string);
            } else {
                return '"' . str_replace(array('\\', '"'), array('\\\\', '\\"'), $p_string) . '"';
            }
        }
        $result = array();
        foreach (func_get_args() as $p_string) {
            $result[] = $this->escape($p_string);
        }
        return $result;
    }

    /**
     * Traduction de l'email
     *
     * @param string $p_email
     * @param string $p_uniqueId
     * @param array  $p_flags
     *
     * @return array
     */
    private function getEmailFormat($p_email, $p_uniqueId = null, array $p_flags = array())
    {
        if (is_array($p_email)) {
            $p_email = implode("\n", $p_email);
        }
        $p_parts = preg_split("/\n\s*\n/", $p_email, 2);
        $head  = $p_parts[0];
        $body  = null;
        if (isset($p_parts[1]) && trim($p_parts[1]) != ')') {
            $body = $p_parts[1];
        }
        $lines = explode("\n", $head);
        $head  = array();
        foreach ($lines as $line) {
            if (trim($line) && preg_match("/^\s+/", $line)) {
                $head[count($head)-1] .= ' '.trim($line);
                continue;
            }
            $head[] = trim($line);
        }
        $head           = implode("\n", $head);
        $recipientsTo   = $recipientsCc = $recipientsBcc = $sender = array();
        $headers1       = imap_rfc822_parse_headers($head);
        $headers2       = $this->getHeaders($head);
        $sender['name'] = null;
        if (isset($headers1->from[0]->personal)) {
            $sender['name'] = $headers1->from[0]->personal;
            if (preg_match("/^\=\?[a-zA-Z]+\-[0-9]+.*\?/", strtolower($sender['name']))) {
                $sender['name'] = str_replace('_', ' ', mb_decode_mimeheader($sender['name']));
            }
        }
        $sender['email'] = $headers1->from[0]->mailbox . '@' . $headers1->from[0]->host;
        if (isset($headers1->to)) {
            foreach ($headers1->to as $to) {
                if (!isset($to->mailbox, $to->host)) {
                    continue;
                }
                $recipient = array('name'=>null);
                if (isset($to->personal)) {
                    $recipient['name'] = $to->personal;
                    if (preg_match("/^\=\?[a-zA-Z]+\-[0-9]+.*\?/", strtolower($recipient['name']))) {
                        $recipient['name'] = str_replace('_', ' ', mb_decode_mimeheader($recipient['name']));
                    }
                }
                $recipient['email'] = $to->mailbox . '@' . $to->host;
                $recipientsTo[]     = $recipient;
            }
        }
        if (isset($headers1->cc)) {
            foreach ($headers1->cc as $cc) {
                $recipient = array('name'=>null);
                if (isset($cc->personal)) {
                    $recipient['name'] = $cc->personal;
                    if (preg_match("/^\=\?[a-zA-Z]+\-[0-9]+.*\?/", strtolower($recipient['name']))) {
                        $recipient['name'] = str_replace('_', ' ', mb_decode_mimeheader($recipient['name']));
                    }
                }
                $recipient['email'] = $cc->mailbox . '@' . $cc->host;
                $recipientsCc[]     = $recipient;
            }
        }
        if (isset($headers1->bcc)) {
            foreach ($headers1->bcc as $bcc) {
                $recipient = array('name'=>null);
                if (isset($bcc->personal)) {
                    $recipient['name'] = $bcc->personal;
                    if (preg_match("/^\=\?[a-zA-Z]+\-[0-9]+.*\?/", strtolower($recipient['name']))) {
                        $recipient['name'] = str_replace('_', ' ', mb_decode_mimeheader($recipient['name']));
                    }
                }
                $recipient['email'] = $bcc->mailbox . '@' . $bcc->host;
                $recipientsBcc[]    = $recipient;
            }
        }
        if (!isset($headers1->subject) || strlen(trim($headers1->subject)) === 0) {
            $headers1->subject = self::NO_SUBJECT;
        }
        $headers1->subject = str_replace(array('<', '>'), '', trim($headers1->subject));
        if (preg_match("/^\=\?[a-zA-Z]+\-[0-9]+.*\?/", strtolower($headers1->subject))) {
            $headers1->subject = str_replace('_', ' ', mb_decode_mimeheader($headers1->subject));
        }
        $topic  = isset($headers2['thread-topic']) ? $headers2['thread-topic'] : $headers1->subject;
        $parent = isset($headers2['in-reply-to']) ? str_replace('"', '', $headers2['in-reply-to']) : null;
        $date   = isset($headers1->date) ? strtotime($headers1->date) : null;
        if (isset($headers2['message-id'])) {
            $messageId = str_replace('"', '', $headers2['message-id']);
        } else {
            $messageId = '<eden-no-id-'.md5(uniqid()).'>';
        }
        $attachment = isset($headers2['content-type'])
        && strpos($headers2['content-type'], 'multipart/mixed') === 0;
        $undelivered = false;
        if (strpos(strtoupper($headers1->subject), 'UNDELIVERED') !== false) {
            $undelivered = true;
        } else {
            if (strpos(strtoupper($headers1->subject), 'DELIVERY HAS FAILED') !== false) {
                $undelivered = true;
            }
        }
        $original = false;
        if ($undelivered && trim($body) && $body != ')') {
            $pos = strpos(strtolower($body), 'final-recipient');
            if ($pos !== false) {
                $string   = substr($body, $pos+15, 200);
                $partst   = explode(';', $string);
                $partst   = explode(PHP_EOL, $partst[1]);
                $original = trim($partst[0]);
            } else {
                $pos = strpos(strtolower($body), 'x-bcc');
                if ($pos !== false) {
                    $string   = substr($body, $pos, 200);
                    $partst   = explode(':', $string);
                    $partst   = explode(PHP_EOL, $partst[1]);
                    $original = trim($partst[0]);
                } else {
                    // @todo...
                }
            }
        }
        $format = array(
            'id'          => $messageId,
            'parent'      => $parent,
            'topic'       => $topic,
            'undelivered' => $undelivered,
            'original'    => $original,
            'mailbox'     => $this->mailbox,
            'uid'         => $p_uniqueId,
            'date'        => $date,
            'subject'     => str_replace('’', '\'', $headers1->subject),
            'from'        => $sender,
            'flags'       => $p_flags,
            'to'          => $recipientsTo,
            'cc'          => $recipientsCc,
            'bcc'         => $recipientsBcc,
            'attachment'  => $attachment
        );
        if (trim($body) && $body != ')') {
            $p_parts = $this->getParts($p_email);
            if (empty($p_parts)) {
                $p_parts = array('text/plain' => $body);
            }
            $body       = $p_parts;
            $attachment = array();
            if (isset($body['attachment'])) {
                $attachment = $body['attachment'];
                unset($body['attachment']);
            }
            $format['body']       = $body;
            $format['attachment'] = $attachment;
        }
        return $format;
    }


    /**
     * Réponse
     *
     * @param string $p_command
     * @param array  $p_parameters
     * @param bool   $p_first
     *
     * @return array
     */
    private function getEmailResponse($p_command, $p_parameters = array(), $p_first = false)
    {
        if (!$this->send($p_command, $p_parameters)) {
            return false;
        }
        $messageId = $uniqueId = $count = 0;
        $emails    = $email = array();
        $start     = time();
        while (time() < ($start + self::TIMEOUT)) {
            $line = str_replace("\n", '', $this->getLine());
            if (strpos($line, 'FETCH') !== false && strpos($line, 'TAG'.$this->tag) === false) {
                if (!empty($email)) {
                    $emails[$uniqueId] = $this->getEmailFormat($email, $uniqueId, $flags);
                    if ($p_first) {
                        return $emails[$uniqueId];
                    }
                    $email = array();
                }
                if (strpos($line, 'OK') !== false) {
                    continue;
                }
                $flags = array();
                if (strpos($line, '\Answered') !== false) {
                    $flags[] = 'answered';
                }
                if (strpos($line, '\Flagged') !== false) {
                    $flags[] = 'flagged';
                }
                if (strpos($line, '\Deleted') !== false) {
                    $flags[] = 'deleted';
                }
                if (strpos($line, '\Seen') !== false) {
                    $flags[] = 'seen';
                }
                if (strpos($line, '\Draft') !== false) {
                    $flags[] = 'draft';
                }
                $findUid = explode(' ', $line);
                foreach ($findUid as $i => $p_uid) {
                    if (is_numeric($p_uid)) {
                        $uniqueId = $p_uid;
                    }
                    if (strpos(strtolower($p_uid), 'uid') !== false) {
                        $uniqueId = $findUid[$i+1];
                        break;
                    }
                }
                continue;
            }
            if (strpos($line, 'TAG'.$this->tag) !== false) {
                if (!empty($email) && strpos(trim($email[count($email) -1]), ')') === 0) {
                    array_pop($email);
                }
                if (!empty($email)) {
                    $emails[$uniqueId] = $this->getEmailFormat($email, $uniqueId, $flags);
                    if ($p_first) {
                        return $emails[$uniqueId];
                    }
                }
                break;
            }
            $email[] = $line;
        }
        return $emails;
    }

    /**
     * Retourne les en-têtes
     *
     * @param string $p_rawData
     *
     * @return array
     */
    private function getHeaders($p_rawData)
    {
        if (is_string($p_rawData)) {
            $p_rawData = explode("\n", $p_rawData);
        }
        $key     = null;
        $headers = array();
        foreach ($p_rawData as $line) {
            $line = trim($line);
            if (preg_match("/^([a-zA-Z0-9-]+):/i", $line, $matches)) {
                $key = strtolower($matches[1]);
                if (isset($headers[$key])) {
                    if (!is_array($headers[$key])) {
                        $headers[$key] = array($headers[$key]);
                    }
                    $headers[$key][] = trim(str_replace($matches[0], '', $line));
                    continue;
                }
                $headers[$key] = trim(str_replace($matches[0], '', $line));
                continue;
            }
            if (!is_null($key) && isset($headers[$key])) {
                if (is_array($headers[$key])) {
                    $headers[$key][count($headers[$key])-1] .= ' '.$line;
                    continue;
                }
                $headers[$key] .= ' '.$line;
            }
        }
        return $headers;
    }

    /**
     * Liste sous forme de chaine
     *
     * @param array $p_array
     *
     * @return string
     */
    private function getList($p_array)
    {
        $list = array();
        foreach ($p_array as $key => $value) {
            $list[] = !is_array($value) ? $value : $this->getList($v);
        }
        return '(' . implode(' ', $list) . ')';
    }

    /**
     * Découpage de la partie body
     *
     * @param string $p_content
     * @param array  $p_parts
     *
     * @return array
     */
    private function getParts($p_content, array $p_parts = array())
    {
        list($head, $body) = preg_split("/\n\s*\n/", $p_content, 2);
        $head = $this->getHeaders($head);
        if (!isset($head['content-type'])) {
            return $p_parts;
        }
        if (is_array($head['content-type'])) {
            $type = array($head['content-type'][1]);
            if (strpos($type[0], ';') !== false) {
                $type = explode(';', $type[0], 2);
            }
        } else {
            $type = explode(';', $head['content-type'], 2);
        }
        $extra = array();
        if (count($type) == 2) {
            $extra = explode('; ', str_replace(array('"', "'"), '', trim($type[1])));
        }
        $type = trim($type[0]);
        foreach ($extra as $i => $attr) {
            $attr = explode('=', $attr, 2);
            if (count($attr) > 1) {
                list($key, $value) = $attr;
                $extra[$key] = $value;
            }
            unset($extra[$i]);
        }
        if (isset($extra['boundary'])) {
            $sections = explode('--'.str_replace(array('"', "'"), '', $extra['boundary']), $body);
            array_pop($sections);
            array_shift($sections);
            foreach ($sections as $section) {
                $p_parts = $this->getParts($section, $p_parts);
            }
        } else {
            if (isset($head['content-transfer-encoding'])) {
                if (is_array($head['content-transfer-encoding'])) {
                    $head['content-transfer-encoding'] = array_pop($head['content-transfer-encoding']);
                }
                switch (strtolower($head['content-transfer-encoding'])) {
                    case 'binary':
                        $body = imap_binary($body);
                        break;
                    case 'base64':
                        $body = base64_decode($body);
                        break;
                    case 'quoted-printable':
                        $body = quoted_printable_decode($body);
                        break;
                    case '7bit':
                        $body = mb_convert_encoding($body, 'UTF-8', 'ISO-2022-JP');
                        break;
                    default:
                        break;
                }
            }
            if (isset($extra['name'])) {
                $p_parts['attachment'][$extra['name']][$type] = $body;
            } else {
                $p_parts[$type] = $body;
            }
        }
        return $p_parts;
    }
}

// HACK
if (!function_exists('imap_rfc822_parse_headers')) {
    function imap_rfc822_parse_headers_decode($from)
    {
        if (preg_match('#\<([^\>]*)#', html_entity_decode($from))) {
            preg_match('#([^<]*)\<([^\>]*)\>#', html_entity_decode($from), $From);
            $from = array(
                'personal' => trim($From[1]),
                'email'    => trim($From[2]));
        } else {
            $from = array(
                'personal' => '',
                'email'    => trim($from));
        }
        preg_match('#([^\@]*)@(.*)#', $from['email'], $from);
        if (empty($from[1])) {
            $from[1] = '';
        }
        if (empty($from[2])) {
            $from[2] = '';
        }
        $__from = array(
            'mailbox' => trim($from[1]),
            'host'    => trim($from[2]));
        return (object) array_merge($from, $__from);
    }

    function imap_rfc822_parse_headers($header)
    {
        $header      = htmlentities($header);
        $headers     = new \stdClass();
        $tos         = $ccs  = $bccs = array();
        $headers->to = $headers->cc = $headers->bcc = array();
        preg_match('#Message\-(ID|id|Id)\:([^\n]*)#', $header, $ID);
        if (count($ID) <= 2) {
            $headers->ID = '';
        } else {
            $headers->ID = trim($ID[2]);
        }
        unset($ID);
        preg_match('#\nTo\:([^\n]*)#', $header, $to);
        if (isset($to[1])) {
            $tos = array(trim($to[1]));
            if (strpos($to[1], ',') !== false) {
                explode(',', trim($to[1]));
            }
        }
        $headers->from = array(new \stdClass());
        preg_match('#\nFrom\:([^\n]*)#', $header, $from);
        $headers->from[0] = imap_rfc822_parse_headers_decode(trim($from[1]));
        preg_match('#\nCc\:([^\n]*)#', $header, $cc);
        if (isset($cc[1])) {
            $ccs = array(trim($cc[1]));
            if (strpos($cc[1], ',') !== false) {
                explode(',', trim($cc[1]));
            }
        }
        preg_match('#\nBcc\:([^\n]*)#', $header, $bcc);
        if (isset($bcc[1])) {
            $bccs = array(trim($bcc[1]));
            if (strpos($bcc[1], ',') !== false) {
                explode(',', trim($bcc[1]));
            }
        }
        preg_match('#\nSubject\:([^\n]*)#', $header, $subject);
        $headers->subject = trim($subject[1]);
        unset($subject);
        preg_match('#\nDate\:([^\n]*)#', $header, $date);
        $date = substr(trim($date[0]), 6);
        $date = preg_replace('/\(.*\)/', '', $date);
        $headers->date = trim($date);
        unset($date);
        foreach ($ccs as $k => $cc) {
            $headers->cc[$k] = imap_rfc822_parse_headers_decode(trim($cc));
        }
        foreach ($bccs as $k => $bcc) {
            $headers->bcc[$k] = imap_rfc822_parse_headers_decode(trim($bcc));
        }
        foreach ($tos as $k => $to) {
            $headers->to[$k] = imap_rfc822_parse_headers_decode(trim($to));
        }
        return $headers;
    }
}
