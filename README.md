# drrr-like-chat

_[RAEADME in English here](https://github.com/drrr-like-chat/drrr-like-chat/blob/master/README.en.md)_

![](https://raw.githubusercontent.com/drrr-like-chat/drrr-like-chat/master/image.png)

## 概要

アニメデュラララに出てくるチャットをモデルに作成したAjaxベースのチャットアプリケーションです。
http://suin.asia/2010/03/26/durarara_like_chat

## 開発動機

* デュララに登場するチャットのインターフェイスが好き
* アルタイムチャットはJavaやFlashが主流だが、PHPでもAjaxを駆使すれば実現可能ではないかという技術的な関心（特別な話ではない）


## 開発方針

* アニメに忠実に


## ライセンス

LICENSEを御覧ください。

## 動作要件

* PHP 5.2.11以上
* mbstring
* /trust_path/xmlへの書き込み権限 [0777]

## 設置方法

* /trust_path/xml に書き込み権限を与える
* setting.dist.phpをsetting.phpにリネームしてから修正してください。

## ビープ音の有効化

このパッケージにはライセンスの都合上、ビープ音の音源が付属しておりません。
ビープ音を有効にするには、各自でsound.mp3を用意し、/jsに配置してください。


## 画像アイコンの追加

アイコンはicon_XXXX.gifという名前で、/cssディレクトリに配置してください。
なお、アイコンを追加した場合は、/css/style.cssを修正する必要があります。（CSSの知識が必要）


## 翻訳・ローカリゼーション

以下の各ディレクトリに、言語コード-国コード.php, 言語コード-国コード.jsをUTF-8エンコードで作ってください。
言語コードは"ISO 639 Language Codes"を参考にしてください。
ja-JP.php, ja-JP.jsをコピー＆リネームして翻訳するのが楽です。

* /trust_path/language/
* /js/language/

例：

* /trust_path/language/en-US.php // 英語（アメリカ）
* /js/language/en-US.js
* /trust_path/language/zh-TW.php // 中国語（台湾）
* /js/language/zh-TW.js
* /trust_path/language/ko-KR.php // 韓国語（韓国）
* /js/language/ko-KR.js

言語対訳ファイルは左が原文、右が翻訳です。
左は修正せず、右を各言語に翻訳するようにしてください。

例：

```
　　　　原文　　　　　　　　　　　　　翻訳
"Please input name." => "名前を決めてください。", // *.php
"Please input name." : "名前を決めてください。", // *.js
```

言語の設定はsetting.phpのDURA_LANGUAGEで指定します。
