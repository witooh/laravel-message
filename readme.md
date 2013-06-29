#Laravel Grid DataProvider#

##Installation##

add in app.config

```php
return array(
    'providers'=>array(
        ...
        ...
        'Witooh\Message\MessageServiceProvider',
    ),

    'alias'=>array(
        '''
        ...
        'Message' => 'Witooh\Message\Facades\Message',
    ),
);
```

##Usage##

###Message###

Message will generate json message data

```php
public function getIndex(){
    return Response::json(Message::success($data, $header);
}

```

The response will be like this
```json
{
    header:{
        status: 200,
        message: 'success'
    },
    body:[
        {id: 1, name: 'test1'},
        {id: 2, nmae: 'test2'}
    ]
}
```

Thow Error Exception to Json Response

```php
public function testExceptioons()
    {
        thow new PermissionException(Message::permission($message));
        thow new AuthenticateException(Message::auth($message));
        thow new NoutFoundException(Message::notfound($message));
        thow new ValidationException(Message::validation($errors));
    }
```