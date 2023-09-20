<?php

namespace Shimoning\DskCvs\Constants;

/**
 * @link https://www.dsk-ec.jp/products/shuunou/oshirase/cvscd_20220601.pdf
 */
enum CompanyCode: string
{
    case SEVEN_ELEVEN               = '010';
    case LAWSON                     = '020';
    case FAMILY_MART                = '030';
    case YAMAZAKI                   = '050';
    case MINI_STOP                  = '080';

    case PAY_B                      = '530';
    case CREDIT_CARD_FOR_LOCAL      = '680';
    case POPLAR                     = '730';
    case J_COIN                     = '750';
    case SEICO_MART                 = '760';

    case AU_PAY                     = '780';
    case D_BARAI                    = '800';
    case LINE_PAY                   = '810';
    case PAY_PAY                    = '820';
    case RAKUTEN_BANK               = '830';
    case YUCHO_PAY                  = '840';
    case MMK                        = '890';

    case POSTAL_TRANSFER            = '910';
    case POSTAL_TRANSFER_COUNTER    = '911';

    public function description(): string
    {
        return match ($this) {
            self::SEVEN_ELEVEN              => 'セブン−イレブン',
            self::LAWSON                    => 'ローソン',
            self::FAMILY_MART               => 'ファミリーマート',
            self::YAMAZAKI                  => 'デイリーヤマザキ、ヤマザキデイリーストアー、ニューヤマザキデイリーストア、ヤマザキスペシャルパートナーショップ',
            self::MINI_STOP                 => 'ミニストップ',

            self::PAY_B                     => 'PayB',
            self::CREDIT_CARD_FOR_LOCAL     => 'クレジットカード集信代行（自治体専用）',
            self::POPLAR                    => 'ポプラ',
            self::J_COIN                    => 'J-Coin請求書払い',
            self::SEICO_MART                => 'セイコーマート、ハマナスクラブ（ハセガワストア、タイエー）',

            self::AU_PAY                    => 'au PAY(請求書支払い)',
            self::D_BARAI                   => 'd払い請求書払い',
            self::LINE_PAY                  => 'LINE Pay請求書支払い',
            self::PAY_PAY                   => 'PayPay請求書払い',
            self::RAKUTEN_BANK              => '楽天銀行コンビニ支払サービス',
            self::YUCHO_PAY                 => '銀行Pay（ゆうちょPay等）',
            self::MMK                       => 'ＭＭＫ設置店',

            self::POSTAL_TRANSFER           => '郵便振替',
            self::POSTAL_TRANSFER_COUNTER   => '郵便振替（窓口）',
        };
    }
}
