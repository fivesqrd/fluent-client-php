Fluent Client Library for PHP
============
Programattic approach to generate and send responsive user notifications via e-mail

### Quick Example ###
```
$messageId = Fluent::message()->create()
    ->setTitle('My little pony')
    ->addParagraph('I love my pony very much.')
    ->addCallout('http://www.mypony.com', 'Like my pony')
    ->setTeaser('This is a teaser')
    ->subject('Testing it')
    ->header('Reply-To', 'christianjburger@me.com')
    ->to('christianjburger@gmail.com')
    //->send(\Fluent\Transport::LOCAL);
    ->send(\Fluent\Transport::REMOTE);
  ```
