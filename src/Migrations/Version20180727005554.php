<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180727005554 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_info (id INT AUTO_INCREMENT NOT NULL, fname VARCHAR(255) NOT NULL, mname VARCHAR(255) DEFAULT NULL, lname VARCHAR(255) NOT NULL, suffix VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) NOT NULL, civil_status VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_users (id INT AUTO_INCREMENT NOT NULL, user_info_id INT DEFAULT NULL, accreditation_info_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(64) NOT NULL, role JSON NOT NULL COMMENT \'(DC2Type:json_array)\', discr VARCHAR(255) NOT NULL, INDEX IDX_C2502824586DFF2 (user_info_id), INDEX IDX_C2502824B3948F2F (accreditation_info_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient_record (id INT AUTO_INCREMENT NOT NULL, diagnosis_category_id INT NOT NULL, patient_id INT NOT NULL, doctor_id INT NOT NULL, checkup_date DATE NOT NULL, checkup_reason LONGTEXT NOT NULL, diagnosis LONGTEXT NOT NULL, payment NUMERIC(10, 2) NOT NULL, INDEX IDX_907A766BDD371000 (diagnosis_category_id), INDEX IDX_907A766B6B899279 (patient_id), INDEX IDX_907A766B87F4FB17 (doctor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clinic (id INT AUTO_INCREMENT NOT NULL, clinic_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, sched_start TIME NOT NULL, sched_end TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clinic_user (clinic_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_DA1105D0CC22AD4 (clinic_id), INDEX IDX_DA1105D0A76ED395 (user_id), PRIMARY KEY(clinic_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE accreditation_info (id INT AUTO_INCREMENT NOT NULL, accreditation_code VARCHAR(255) NOT NULL, accreditation_date DATE NOT NULL, accreditation_exp_date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_users ADD CONSTRAINT FK_C2502824586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id)');
        $this->addSql('ALTER TABLE app_users ADD CONSTRAINT FK_C2502824B3948F2F FOREIGN KEY (accreditation_info_id) REFERENCES accreditation_info (id)');
        $this->addSql('ALTER TABLE patient_record ADD CONSTRAINT FK_907A766BDD371000 FOREIGN KEY (diagnosis_category_id) REFERENCES diagnosis_category (id)');
        $this->addSql('ALTER TABLE patient_record ADD CONSTRAINT FK_907A766B6B899279 FOREIGN KEY (patient_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE patient_record ADD CONSTRAINT FK_907A766B87F4FB17 FOREIGN KEY (doctor_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE clinic_user ADD CONSTRAINT FK_DA1105D0CC22AD4 FOREIGN KEY (clinic_id) REFERENCES clinic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clinic_user ADD CONSTRAINT FK_DA1105D0A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_users DROP FOREIGN KEY FK_C2502824586DFF2');
        $this->addSql('ALTER TABLE patient_record DROP FOREIGN KEY FK_907A766B6B899279');
        $this->addSql('ALTER TABLE patient_record DROP FOREIGN KEY FK_907A766B87F4FB17');
        $this->addSql('ALTER TABLE clinic_user DROP FOREIGN KEY FK_DA1105D0A76ED395');
        $this->addSql('ALTER TABLE clinic_user DROP FOREIGN KEY FK_DA1105D0CC22AD4');
        $this->addSql('ALTER TABLE app_users DROP FOREIGN KEY FK_C2502824B3948F2F');
        $this->addSql('DROP TABLE user_info');
        $this->addSql('DROP TABLE app_users');
        $this->addSql('DROP TABLE patient_record');
        $this->addSql('DROP TABLE clinic');
        $this->addSql('DROP TABLE clinic_user');
        $this->addSql('DROP TABLE accreditation_info');
    }
}
