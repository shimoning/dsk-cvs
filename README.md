# DSK CVS API client
電算システムが提供する「インターネット受付システム」サービスの機能「収納データPOST方式取得」をPHPから利用するためのライブラリです。

## Warning
このライブラリはあくまで提供されているサービスをPHPから扱いやすくするためのもので、こちらではサービス自体についてのお問い合わせには答えられません。
また、このライブラリに関するお問い合わせもサービス提供されてる方へ行わないでください。

## Install
利用するプロジェクトの `composer.json` に以下を追加します。
```composer.json
"repositories": {
    "dsk-cvs": {
        "type": "vcs",
        "url": "https://github.com/shimoning/dsk-cvs.git"
    }
},
```

その後以下でインストールが可能です。

```bash
composer require shimoning/dsk-cvs
```

## Prepare
API を利用するためには登録が必要です。
以下の情報をあらかじめ取得しておいてください。
* ログイン企業ID
* システム接続パスワード (ログインパスワード **ではない**)

## Usage

### Basis
基本的な利用方法。

```php
use Shimoning\DskCvs\Client;
use Shimoning\DskCvs\Entities\Options;
use Shimoning\DskCvs\Entities\Input;

$options = new Options(['test' => true]);   // 本番モードの場合は options は null で問題ない
$input = new Input($userId, $pw); // 利用登録後に通知される 「ログイン企業ID」と「システム接続パスワード」をセットしてください。
$recordsOrError = Client::requestCvsRecords($input, $options);
if ($recordsOrError->hasError()) {
    // error handling
    throw new Exception($recordsOrError->getMessage());
    ...
} else {
    // success
    $count = $recordsOrError->count(); // 取得件数
    foreach ($recordsOrError as $record) {
        // do something
        ...
    }
}
```

### Span
取得期間を設定することができます。
片方だけ指定したり、from/to で日付を逆にすることはできません。

```php
use Shimoning\DskCvs\Values\Date;
$fromDate = new Date('20220901');
$toDate = new Date('20220910');
$input = new Input($userId, $pw, $fromDate, $toDate);
```

## CLI
コマンドラインから以下で実行可能です。
```bash
php client
```

### 終了方法
終了する際は `exit` もしくは `Control + C` を入力してください。

### .env
`.env` ファイルを用意することで、`$options` と `$input` がデフォルトでセットされます。
ファイルの中身は `.env.example` を参考にしてください。
またコマンドライン中で `$_ENV['USER_ID']` と呼び出すことが可能です。

## ライセンスについて
当ライブラリは *MITライセンス* です。
[ライセンス](LICENSE) を読んでいただき、範囲内でご自由にご利用ください。
