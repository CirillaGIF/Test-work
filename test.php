<?php


if (!empty($_POST['name']) && !empty($_POST['phone']) && isset($_POST["r1"])) {
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $comment = htmlspecialchars($_POST['comment']);
    $radio = $_POST["r1"];
    $date = date("Y.m.d");
    $time = date("h:i:sa");
    if ($radio == 'amo') {
        amo();
    } else {
        btrx();
    }
}

function set_curl_options($curl, $link, $data, $headers)
{
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
}

function amo()
{
    global $name;
    global $phone;
    global $date;
    global $time;
    global $comment;
    $subdomain = 'rgurzhiyanc29';
    $access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImNmZTY1ZTVhMDc2NGU5MzMzNTAxNjNjYTE0MjQ1NzRhMjliY2RjZjVlZGNmM2U1MjZlOTEyMGY5ZTVlOTI1ZTE2NDFhYzBhYjI5NjdjNzhiIn0.eyJhdWQiOiIxZjk3ODZhMi1lMTAwLTRiZDgtYWE3NC0zODU2OGY2YWUyNTYiLCJqdGkiOiJjZmU2NWU1YTA3NjRlOTMzMzUwMTYzY2ExNDI0NTc0YTI5YmNkY2Y1ZWRjZjNlNTI2ZTkxMjBmOWU1ZTkyNWUxNjQxYWMwYWIyOTY3Yzc4YiIsImlhdCI6MTcyNzM1NzAyNiwibmJmIjoxNzI3MzU3MDI2LCJleHAiOjE3MzA0MTkyMDAsInN1YiI6IjExNTY5Njc0IiwiZ3JhbnRfdHlwZSI6IiIsImFjY291bnRfaWQiOjMxOTcyOTYyLCJiYXNlX2RvbWFpbiI6ImFtb2NybS5ydSIsInZlcnNpb24iOjIsInNjb3BlcyI6WyJjcm0iLCJmaWxlcyIsImZpbGVzX2RlbGV0ZSIsIm5vdGlmaWNhdGlvbnMiLCJwdXNoX25vdGlmaWNhdGlvbnMiXSwiaGFzaF91dWlkIjoiZWExZTA4NTMtOTFhOS00YWRjLTkzNmQtOTY5OTM4YzY4ZDU1IiwiYXBpX2RvbWFpbiI6ImFwaS1iLmFtb2NybS5ydSJ9.SHJUNA-C9UcHzSXvh1tc-oveh8g995WtOrnYopxnmZSMT9wjlvE-yEl2zrl66t9Gvh0dwjh52-SiClEEDihKbS2CrF6GGoIS6GwRt-89pHz8Xauo9YIz-c4YQEitq_62c0uzjL8BU0UgSfVIhM7E2LncRVMbqauLKBAOsyn7H2ZpTXBGus61xN430PLxPqGb-8393L1KCXjI6wTQLfFlzFsn_UUPhNOSX8eTcs8x7EojTzhsZWDWBXs80oNgzvDXJiQRL66J9UEE9mwy5A89L3bZwQnkag5T6IoONnc99tN0WmrCDpSxd2VoHIvPow6N-IleFSdDing_zTmEeEID_w';
    $link = "https://$subdomain.amocrm.ru/api/v4/";
    $endpoint = 'contacts';
    $tag_id = 10;
    $custom_field_id = 363209;
    $comment_field_id = 363211;
    $phone_field_id = 381649;
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
    ];

    $values = [[
        'value' => $phone
    ]];
    $custom_fields_values = [[
        'field_id' => $phone_field_id,
        'field_name' => 'Телефон',
        'field_code' => 'PHONE',
        'field_type' => 'multitext',
        'values' => $values
    ]];
    $tags_to_add = [
        ['name' => 'Сайт'],
        ['id' => $tag_id]
    ];
    $contact = [[
        'name' => $name,
        'custom_fields_values' => $custom_fields_values,
        'tags_to_add' => $tags_to_add
    ]];
    
    $contact = json_encode($contact, JSON_UNESCAPED_UNICODE);
    //echo ("$contact <br><br>"); 
    $curl = curl_init();
    set_curl_options($curl, $link . $endpoint, $contact, $headers);
    $out = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $code = (int) $code;
    curl_close($curl);
    $out = json_decode($out);
    $contact_id = $out->_embedded->contacts[0]->id;
    //echo("$code <br>");//
    $custom_fields_values = null;
    $values = null;
    $endpoint = 'leads';

    $values = [[
        'value' => 'Сайт'
    ]];
    $custom_fields_values = [
        [
            'field_id' => $custom_field_id,
            'values' => $values
        ],
        [
            'field_id' => $comment_field_id,
            'values' => [[
                'value' => $comment
            ]]
        ]
    ];

    $tags = [[
        'id' => $tag_id
    ]];
    $contacts = [[
        'id' => $contact_id
    ]];
    $_embedded = [
        'contacts' => $contacts
    ];
    $deal = [[
        'name' => "Заявка с сайта $date \ $time",
        'custom_fields_values' => $custom_fields_values,
        'tags_to_add' => $tags_to_add,
        '_embedded' => $_embedded
    ]];
    $code1 = null;
    $deal = json_encode($deal, JSON_UNESCAPED_UNICODE);
    $curl = curl_init();
    //echo ("$deal <br><br>");//
    set_curl_options($curl, $link . $endpoint, $deal, $headers);
    $out = curl_exec($curl);
    $code1 = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $code1 = (int) $code1;
    curl_close($curl);
    if ($code == 200 && $code1 == 200)
    {
        echo('Ok');
    }
    else
    {
        echo("$code | $code1");
    }
    //var_dump ($out);//
    header("Location: http://rgurzh3j.beget.tech/form.php");
}

function btrx()
{
    global $name;
    global $phone;
    global $date;
    global $time;
    global $comment;
    $headers = [
        'Content-Type: application/json'
    ];
    $curl = curl_init();
    $url = "https://b24-9s6cpq.bitrix24.ru/rest/1/tdoeua2dvgt6102x/";
    $endpoint = 'crm.contact.add';
    $contact = [
        'NAME' => $name,
        'PHONE' => [['VALUE' => "$phone", 'VALUE_TYPE' => 'WORK']]
    ];
    $fields = [
        'fields' => $contact
    ];
    $fields = json_encode($fields, JSON_UNESCAPED_UNICODE);
    set_curl_options($curl, $url . $endpoint, $fields, $headers);
    $out = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $code = (int) $code;
    curl_close($curl);
    echo ($code);
    $out = json_decode($out);
    $contact_id = $out->result;

    $fields = null;
    $endpoint = 'crm.deal.add';
    $custom_field_id = 45;
    $deal = [
        'TITLE' => "Заявка с сайта $date \ $time",
        'CONTACT_ID' => $contact_id,
        'COMMENTS' => $comment,
        'UF_CRM_1711955837997' => $custom_field_id
    ];
    $fields = [
        'fields' => $deal
    ];
    $fields = json_encode($fields, JSON_UNESCAPED_UNICODE);
    $curl = curl_init();
    set_curl_options($curl, $url . $endpoint, $fields, $headers);
    echo ($fields);
    $out = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $code = (int) $code;
    curl_close($curl);
    // echo ($code);
}
?>