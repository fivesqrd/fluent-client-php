Fluent for PHP
============
Programmatic approach to generating and sending responsive user notifications via e-mail

### Benefits ###
- Easy API to generate HTML based e-mail bodies in your app
- Less time wrestling with CSS inlining
- Automatically responsive

### Install ###

```
php composer.phar require fivesqrd/fluent:4.0
```

For Laravel projects there is an easy to install package available
```
composer require fivesqrd/fluent-laravel
```

## Register
To send messages you'll first need to [register](http://fluentmsg.com) Fluent account. Once registered, you'll receive API key to
start sending messages immediately.

### Quick Examples ###
Create and send a message:
```
$messageId = Fluent\Factory::message()->create()
    ->title('My little pony')
    ->paragraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ornare pellentesque neque non rutrum. Sed a sagittis lacus.')
    ->number(['caption' => 'Today', value => date('j M Y')])
    ->button('http://www.mypony.com', 'Like my pony')
    ->paragraph('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.')
    ->when(date('D') == 'Sun', function ($message) {
        $message->paragraph('Something that will only display on Sundays');
    })
    ->teaser('This is a teaser')
    ->subject('Testing it')
    ->header('Reply-To', 'me@myapp.com')
    ->to('user@theirdomain.com')
    ->send();
```

The following methods are provided to set up the delivery of the message:

### header($key, $value) or headers($values)
```
/* Add a header to the message */
$message->header('Reply-To', 'me@myapp.com');
```

```
/* Add multiple headers to the message */
$message->headers(array(
    'Reply-To', 'me@myapp.com',
    'X-Fluent', 'lorem'
));
```

### from($address, $name = null)
```
/* Set the sender address and name */
$message->from('me@myapp.com', 'My App');
```

### attach($filename, $mimetype, $blob) or attachments($values)
```
/* Add an attachment to the message */
$message->attach('My-Attachment.pdf', 'application/pdf', file_get_contents($file))
```

```
/* Only add an attachment if the condition is satisfied */
$message->attachWhen(file_exists($file), 'My-Attachment.pdf', 'application/pdf', file_get_contents($file))
```

```
/* Add multiple attachments to the message */
$message->attachments(array(
    ['name' => 'My-First-File.pdf', 'type' => 'application/pdf', 'content' => file_get_contents($file)],
    ['name' => 'My-2nd-File.jpg', 'type' => 'image/jpg', 'content' => file_get_contents($file2)],
));
```

### send()
Send is the final method of the chain and should always be called last. It delivers to message to the Fluent Web Service and returns a unique message ID.
```
/* Send the message */
$messageId = $message->send();
```

### Plain Text ###
```
$messageId = Fluent\Factory::message()
    ->create('This is a plan text email body')
    ->subject('Testing it')
    ->header('Reply-To', 'me@myapp.com')
    ->to('user@theirdomain.com')
    ->send();
```

Find problematic events related to a user's email adress:
```
$response = Fluent\Factory::event()->find()
    ->to('user@theirdomain.com')
    ->since(date('Y-m-d H:i:s', $timeframe))
    ->type(['hard_bounce', 'soft_bounce', 'reject'])
    ->fetch();
```
