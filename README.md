#### GMail API PHP Starter
GMAIL PHP Starter Project built using Twitter Bootstrap3

#### PHP Requirements
PHP 5.4+ is now required by the upgraded Mime Mail Parser [muffycompo/php-mime-mail-parser](https://github.com/muffycompo/php-mime-mail-parser) which requires the [mailparse](http://php.net/manual/en/book.mailparse.php) php extension.

#### Installation

######Step 1:
    git clone https://github.com/gdgbhu/gmail-php-starter.git
    cd gmail-php-starter/
    bower install
	composer install

######Step 2:
Edit `helpers/config.ini` and add your API console credentials

    [credentials]
    client_id = "<CLIENT ID>"
    client_secret = "<CLIENT SECRET>"
    redirect_url = "<REDIRECT URI>"

#### Credits
This GMail API PHP Starter project is provided by [GDG Bingham University](http://bhu.gdg.ng) as an example of using the GMail API with vanilla/procedural PHP.
 
**Note:** Do not use this as production code. It is provided as a reference to get you started building amazing apps.