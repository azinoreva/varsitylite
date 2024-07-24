CREATE TABLE `varsitylite`.`library` (
    `Serial_number` INT NOT NULL AUTO_INCREMENT,
    `Title` TEXT NOT NULL,
    `Author` TEXT NOT NULL,
    `Date_of_Publication` DATE NOT NULL,
    `Document` BLOB NOT NULL,
    `Uploaded_by` VARCHAR(256) NOT NULL,
    PRIMARY KEY (`Serial_number`)
) ENGINE = InnoDB;
