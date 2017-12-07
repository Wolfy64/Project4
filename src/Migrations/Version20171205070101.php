<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171205070101 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ticket ADD reservation_id INT DEFAULT NULL, ADD price_type VARCHAR(255) NOT NULL, ADD amount NUMERIC(10, 0) NOT NULL, ADD reducedPrice TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3B83297E7 ON ticket (reservation_id)');
        $this->addSql('ALTER TABLE guest ADD ticket_id INT DEFAULT NULL, ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, ADD date_of_birth DATE NOT NULL, ADD country VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE guest ADD CONSTRAINT FK_ACB79A35700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ACB79A35700047D2 ON guest (ticket_id)');
        $this->addSql('ALTER TABLE reservation ADD email VARCHAR(255) NOT NULL, ADD visit_type VARCHAR(255) NOT NULL, ADD cost NUMERIC(10, 0) NOT NULL, ADD date DATETIME NOT NULL, ADD booking_date DATETIME NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE guest DROP FOREIGN KEY FK_ACB79A35700047D2');
        $this->addSql('DROP INDEX UNIQ_ACB79A35700047D2 ON guest');
        $this->addSql('ALTER TABLE guest DROP ticket_id, DROP first_name, DROP last_name, DROP date_of_birth, DROP country');
        $this->addSql('ALTER TABLE reservation DROP email, DROP visit_type, DROP cost, DROP date, DROP booking_date');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3B83297E7');
        $this->addSql('DROP INDEX IDX_97A0ADA3B83297E7 ON ticket');
        $this->addSql('ALTER TABLE ticket DROP reservation_id, DROP price_type, DROP amount, DROP reducedPrice');
    }
}
