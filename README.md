Message Bird Task
===============================
Since I was specifically asked not to use not any frameworks I have created my own mini framework to handle routes , requests, validation rules and responses .


Installation
-----
* Go to migrations folder and use the sql script to create messages table
* Go to daemons folder and type the following in the terminal to run the MessageDispatcher in the background

```bash

ls -l & exec php MessageDispatcher.php > /dev/null 2>&1 & echo $!

```

Usage
----

* index.php acts like a front controller and all routes need to be configured under Http/routes.php

in routes.php

```php

return [
    [
        'name'      => 'sms_send_v1',
        'method'    => 'POST',
        'route'     => 'sms/send',
        'version'   => 'v1'
    ],
    [
        'name'      => 'sms_send_v2',
        'method'    => 'POST',
        'route'     => 'sms/send',
        'version'   => 'v2'
    ]
];

```
in index.php

```php

switch ($validRoute['route']['name']) {
    case 'sms_send_v1':
        // do something
        break;
    case 'sms_send_v2':
        // do something
        break;
    ...
}

```

* To add another service request you can simply create a class extended from Request and overide isValid() method to validate according to your desire

```php

class SendSMSRequest extends Request
{

    public function isValid()
    {
        $rules = [
            'recipient'  => 'required|phone',
            'originator' => 'required|originator',
            'message'    => 'required|sms',
        ];

        $validator = new Validator($this);
        $result = $validator->validate($rules);

        if (!$result) {
            $response = new Response(422, $validator->getErrors());
            $response->sendJSON();
        }

        return $result;
    }

}

```

Documentation
----


