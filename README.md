Fluent for PHP
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
    ->addParagraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ornare pellentesque neque non rutrum. Sed a sagittis lacus.')
    ->addCallout('http://www.mypony.com', 'Like my pony')
    ->addParagraph('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.')
    ->setTeaser('This is a teaser')
    ->subject('Testing it')
    ->header('Reply-To', 'me@myapp.com')
    ->to('myemail@email.com')
    ->send();
  ```
