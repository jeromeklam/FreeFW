<?php
namespace FreeFW\Storage\Migrations\V20210130104500;

/**
 *
 * @author jeromeklam
 *
 */
class Database extends \FreeFW\Storage\Migrations\AbstractMigration {

    /**
     * Migrate all editions
     */
    protected function migrateEditions()
    {
        $editions = \FreeFW\Model\Edition::find();
        /**
         * @var \FreeFW\Model\Edition $oneEdition
         */
        foreach ($editions as $oneEdition) {
            $ediLang = new \FreeFW\Model\EditionLang();
            $ediLang
                ->setEdiId($oneEdition->getEdiId())
                ->setLangId($oneEdition->getLangId())
                ->setEdilData($oneEdition->getEdiData())
            ;
            if (!$ediLang->create()) {
                var_dump($ediLang->getErrors());
            }
        }
        return true;
    }

    /**
     * Migrate all emails
     */
    protected function migrateEmails()
    {
        $emails = \FreeFW\Model\Email::find();
        /**
         * @var \FreeFW\Model\Email $oneEmail
         */
        foreach ($emails as $oneEmail) {
            $emailLang = new \FreeFW\Model\EmailLang();
            $emailLang
                ->setEmailId($oneEmail->getEmailId())
                ->setLangId($oneEmail->getLangId())
                ->setEmaillSubject($oneEmail->getEmailSubject())
                ->setEmaillBody($oneEmail->getEmailBody())
            ;
            $emailLang->create();
        }
        return true;
    }

    /**
     *
     * @return bool
     */
    public function up() : bool
    {
        $this
            ->addMethod('migrateEditions')
            ->addMethod('migrateEmails')
        ;
        $this->methodUp();
        return true;
    }

    /**
     *
     * @return bool
     */
    public function down() : bool
    {
        return true;
    }
}
