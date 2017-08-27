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

### Send new sms

* use url - v1/sms/send

| Parameters        | Type           | Description  |
| :------------: |:-------:| :-----------------|
| reciepent     | String | A valid phone number. **Required** |
| originator    | String      |   The sender of the message. This can be a telephone number (including country code) or an alphanumeric string. In case of an alphanumeric string, the maximum length is 11 characters. **Required**|
| message       | String      |    sms text with no more than 1377 characters. **Required** |

#### success response (with status code 200)
(ideally this should give 201 with another service to get if the message was delivered)

```json
{
    "status": true,
    "message": {
        "status": "SUCCESS",
        "id": "15"
    }
}

```

#### unsuccessful response (with status code 422)

```json

{
    "status": false,
    "message": {
        "errors": {
            "recipient": {
                "code": 2001,
                "message": "field is not a valid phone number!"
            },
            "originator": {
                "code": 1002,
                "message": "field is not valid!"
            },
            "message": {
                "code": 1001,
                "message": "field is required!"
            }
        }
    }
}

```

