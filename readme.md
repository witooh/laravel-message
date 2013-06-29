#Laravel Message#

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
```js
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
        throw new PermissionException(Message::permission($message));
        throw new AuthenticateException(Message::auth($message));
        throw new NoutFoundException(Message::notfound($message));
        throw new ValidationException(Message::validation($errors));
    }
```