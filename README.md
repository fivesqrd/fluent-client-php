Fluent for PHP
============
Programmatic approach to generating and sending responsive user notifications via e-mail

### Benefits ###
- Easy API to generate HTML based e-mail bodies in your app
- Less time wrestling with CSS inlining
- Automatically responsive

### Install ###
```
php composer.phar require fivesqrd/fluent:3.3
```

### Quick Examples ###
Create and send a message:
```
$messageId = Fluent::message()->create()
    ->setTitle('My little pony')
    ->addParagraph('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ornare pellentesque neque non rutrum. Sed a sagittis lacus.')
    ->addNumber(['caption' => 'Today', value => date('j M Y')])
    ->addButton('http://www.mypony.com', 'Like my pony')
    ->addParagraph('Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.')
    ->setTeaser('This is a teaser')
    ->subject('Testing it')
    ->header('Reply-To', 'me@myapp.com')
    ->to('user@theirdomain.com')
    ->send();
```


Find problematic events related to a user's email adress:
```
$response = \Fluent::event()->find()
    ->to('user@theirdomain.com')
    ->since(date('Y-m-d H:i:s', $timeframe))
    ->type(['hard_bounce', 'soft_bounce', 'reject'])
    ->fetch();
```
