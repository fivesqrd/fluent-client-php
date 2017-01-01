Fluent Client Library for PHP
============
Programattic approach to generate and send responsive user notifications via e-mail

### Benefits ###
- Easy API to generate HTML based e-mail bodies in your app
- Less time wrestling with CSS inlining
- Automatically responsive

### Quick Example ###
```
$messageId = Fluent::message()->create()
    ->setTitle('My little pony')
    ->addParagraph('I love my pony very much.')
    ->addCallout('http://www.mypony.com', 'Like my pony')
    ->setTeaser('This is a teaser')
    ->subject('Testing it')
    ->header('Reply-To', 'me@myapp.com')
    ->to('myemail@email.com')
    ->send(\Fluent\Transport::REMOTE);
  ```
