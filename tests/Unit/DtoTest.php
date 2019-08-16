<?php

namespace Tests\Unit;


use App\User;
use Domain\User\UserMailer;
use Dto\Dto;
use Dto\DtoBuilder;
use Dto\DtoService;
use Illuminate\Mail\Message;
use Mail;
use Tests\TestCase;
use Tool\Mailer;

class DemoDto implements Dto
{

    public function dto(DtoBuilder $dtoBuilder)
    {
        $dtoBuilder->add('key1')
            ->add('key2');
    }
}

class DtoTest extends TestCase
{
    public function testDto()
    {
        $rs = resolve(DtoService::class)->transfer(
            ['key1' => 123, 'key2' => 456,],
            function (DtoBuilder $dtoBuilder) {
                $dtoBuilder->add('key1');
                $dtoBuilder->add('key2');
            }
        );
        $this->assertEquals(['key1' => 123, 'key2' => 456,], $rs);


    }

//    public function testMail(){
////        dd(env('MAIL_HOST'));
////        resolve(Mailer::class)->send('348578429@qq.com','haha','123456');
//        // Mail::send()的返回值为空，所以可以其他方法进行判断
//        $user = User::query()->where('email','348578429@qq.com')->first();
//        resolve(UserMailer::class)->register($user);
//        // 返回的一个错误数组，利用此可以判断是否发送成功
//        dd(Mail::failures());
//
////        Mail::send("index",['name'=>'hahaha'], function ($message) use ($email) {
//////            $message->from('jb@16777222.com', '放肆');
////            $message ->to($email)->subject('邮件测试');
////
////        });
//    }
    public function testList()
    {
        $rs = resolve(DtoService::class)->transfer(
            [
                'key1' => 123,
                'key2' => ['key3' => 'hahaha', 'key4' => 'key4'],
            ],
            function (DtoBuilder $dtoBuilder) {
                $dtoBuilder->add('key1');
                $dtoBuilder->add('key2', ['using' => function (DtoBuilder $dtoBuilder) {
                    $dtoBuilder->add('key3');
                    $dtoBuilder->add('key4');
                }]);
            }
        );
        $this->assertEquals([
            'key1' => 123,
            'key2' => ['key3' => 'hahaha', 'key4' => 'key4'],
        ], $rs);
    }

    public function testAs()
    {
        $rs = resolve(DtoService::class)->transfer(
            [
                'key1' => 123,
                'key2' => 456,
            ],
            function (DtoBuilder $dtoBuilder) {
                $dtoBuilder->add('key1');
                $dtoBuilder->add('key2', ['as' => 'key3']);
            }
        );
        $this->assertEquals([
            'key1' => 123,
            'key3' => 456,
        ], $rs);
    }

    public function testDtoClass()
    {
        $rs = resolve(DtoService::class)->transfer(
            [
                'key1' => 123,
                'key2' => 456,
            ], DemoDto::class
        );
        $this->assertEquals([
            'key1' => 123,
            'key2' => 456,
        ], $rs);
    }

    public function testDtoClass2()
    {
        $rs = resolve(DtoService::class)->transfer(
            [
                'key1' => 123,
                'key2' => [
                    'key1' => 123,
                    'key2' => 456,
                ],
            ], function (DtoBuilder $dtoBuilder) {
            $dtoBuilder->add('key1');
            $dtoBuilder->add('key2', ['using' => DemoDto::class]);
        }
        );
        $this->assertEquals([
            'key1' => 123,
            'key2' => [
                'key1' => 123,
                'key2' => 456,
            ],
        ], $rs);
    }


    public function testFormat1()
    {
        $rs = resolve(DtoService::class)->transfer(
            ['key1' => "123.1", 'key2' => 456,],
            function (DtoBuilder $dtoBuilder) {
                $dtoBuilder->add('key1', ['format' => function ($v) {
                    return intval($v);
                }]);
                $dtoBuilder->add('key2');
            }
        );

        $this->assertEquals([
            'key1' => 123,
            'key2' => 456,
        ], $rs);
    }

    public function testFormat2()
    {
        $rs = resolve(DtoService::class)->transfer(
            ['key1' => 123.1, 'key2' => 456,],
            function (DtoBuilder $dtoBuilder) {
                $dtoBuilder->add('key1', ['format' => 'string']);
                $dtoBuilder->add('key2');
            }
        );

        $this->assertEquals([
            'key1' => "123.1",
            'key2' => 456,
        ], $rs);
    }
    public function testMethod()
    {
        $rs = resolve(DtoService::class)->transfer(
            ['key1' => ['key3'=>111], 'key2' => ['key3'=>123],],
            function (DtoBuilder $dtoBuilder) {
                $dtoBuilder->add('key1.key3', ['format' => 'string']);
                $dtoBuilder->add('key2.key3', ['format' => 'string','as'=>'key4']);
            }
        );

        $this->assertEquals([
            'key1.key3' => "111",
            'key4' => "123",
        ], $rs);
    }
}