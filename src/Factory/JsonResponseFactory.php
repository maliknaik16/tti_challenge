<?php

namespace App\Factory;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class JsonResponseFactory
{
    private SerializerInterface $serializer;
    private $allowed_fields;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->allowed_fields = [
            'id',
            'firstName',
            'lastName',
            'email',
            'address',
            'phone',
            'role',
            'createdAt'
        ];
    }

    public function create(object $data): array
    {

        $resp = json_decode($this->serializer->serialize($data, JsonEncoder::FORMAT), true);
        $temp = [];

        // dd($resp);
        for($i = 0; $i < count($this->allowed_fields); $i++)
        {
            $temp[$this->allowed_fields[$i]] = $resp[$this->allowed_fields[$i]];
        }

        return $temp;
        // return new Response(
        //     $this->serializer->serialize($data, JsonEncoder::FORMAT),
        //     $status,
        //     array_merge($headers, ['Content-Type' => 'application/json;charset=UTF-8'])
        // );
    }
}
