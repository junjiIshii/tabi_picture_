# TABI PICTURE
---

## 概要
TABI PICTUREは練習用兼、ポートフォリオとして作成した写真売買Webサービスです。ピクスタとインスタグラムを混ぜ合わせたもどきサービスと考えて頂けたら分かりやすいと思います。実際の売買の機能はついていませんが、スマホで撮影した写真を気軽に売買できればいいなという思いつきで作成しました。下記のような機能を持ち合わせ、ここでTABI PICTUREの使い方・特徴と開発で工夫したこと、大変だったことなどを纏めて掲載しています。ポートフォリオのご査収よろしくお願いします。

## 公開URL
https://jun-app.com/tabi_picture/index.php

---
---

## スクリーンショット解説
### 実装機能一覧

- ログイン機能（会員制機能）
- 通知
- ユーザーフォロー
- 画像アップロード
- 出品物の再編集、非公開化、削除
- プロフィール編集
- ユーザー間のダイレクメッセージ
- 商品、ユーザーの検索
- 入力値のバリデーションチェック
- お気に入り登録(Ajax)

### メインページ
---

#### [トップページ](https://test.english-protocol.net/tabi_picture/index.php)

トップページはこのサイトの概要と機能紹介が載っています。また「ゲストユーザー」を選択することで、アドレス登録をするとなく中身の機能を見ることができます。ログインをしていなくても「写真を見る」なで登録されている写真・ユーザーの閲覧は可能です。


![トップページ](https://github.com/junjiIshii/readmeImg/blob/master/tabi_picrue/indexPage.png)

#### [登録商品一覧](https://test.english-protocol.net/tabi_picture/products_list.php)
商品一覧では登録された商品の簡単な詳細を見ることができます。 __カードをクリックするとその商品の詳細ページに飛びます。__ カードの右上にある __「灰色のハート」をクリックすると、お気に入り登録が可能__ です。ログインをしていれば、マイページの「お気に入り一覧」から後で見れます。左側のカラムでは、 __商品タイトル・カテゴリーでの検索、表示数と新旧順入れ替えの操作__ が可能です。登録商品数が表示数を上回る場合は、ページングで分けられます。

![商品一覧](https://github.com/junjiIshii/readmeImg/blob/master/tabi_picrue/productList.png)

#### [ユーザー一覧](https://test.english-protocol.net/tabi_picture/users_list.php)
ユーザー一覧では登録したユーザーの一覧を見ることができます。 __カードをクリックするとそのユーザーの詳細に飛びます。__ またログインした状態で閲覧すると __ユーザーのフォローが可能です。__ ユーザーをフォローすると、フォローした人が新たに商品を登録した時に通知が行くようになります。他のページでも __アイコンをクリックするとそのユーザーの詳細ページに飛ぶことができます。__ ユーザー数が表示数を上回る場合は、ページングで分けられます。


![ユーザー一覧](https://github.com/junjiIshii/readmeImg/blob/master/tabi_picrue/userName.png)


#### 商品詳細
カードをクリックすると詳細画面が開きます。 画像横の「＜ ＞」で登録された複数の画像を閲覧できます。 __「作者にDM」のボタンを押すと、その作者に対してDMを送ることができます。（ログインが必要です）__ このページからでも「お気に入り登録」ができます。

![商品詳細](https://github.com/junjiIshii/readmeImg/blob/master/tabi_picrue/productDetail.png)


#### ユーザー詳細 
ユーザーのアイコン、またはカードをクリックするとそのユーザーの詳細画面が開きます。この画面でもフォロー、ダイレクトメッセージ(※以下DM)を送ることができます。またそのユーザーが登録した商品の一覧も見ることができます。

![ユーザー詳細 ](https://github.com/junjiIshii/readmeImg/blob/master/tabi_picrue/userDetail.png)


### 個人のページ
---
#### マイページ
ログインをするとマイページを持つことができます。このページでは、 __商品アップロード、届いたDMの一覧、プロフィール編集、商品編集、パスワード変更、お気に入り一覧、通知一覧__ へのアクセスができます。通知ではその内容をクリックすると、DMの場合はそのDMを表示し、フォローではその人のユーザー詳細画面に飛びます。

![マイページ](https://github.com/junjiIshii/readmeImg/blob/master/tabi_picrue/mypage.png)

#### 商品登録＆編集
編集画面と登録画面のファイルは共通であり、データベース上の有無によって判別されています。 __最大9枚の画像をアップロード__ することができます。ファイルはドロップでアップロードすることもできます。最低1枚の写真、商品名、カテゴリー、商品詳細、金額を入力してアップロードできます。入力した値やファイルには __バリデーション（MIMEタイプ、サイズ、半角入力など）が適用__ されます。編集では登録した内容の変更ができます。また __非公開化や商品の削除__ も可能です。

![商品登録](https://github.com/junjiIshii/readmeImg/blob/master/tabi_picrue/productEdit.png)

#### ダイレクトメッセージ（DM）
__DMではユーザー同士のプラーベートメッセージを送り合うことができます。__ サービス的な意図として、商用利用の許可の連絡や値段の交渉、感謝メッセージなどを送る際に使用してもらうことを想定しています。左側の「メッセージリスト」から送信相手を選び、メッセージを入力して「メッセージ送信」をクリックして送信します。新しい人にDMを送りたい場合は、送りたい相手のユーザー詳細画面や、出品している商品ページから「DMを送る」・「作者にDM」を選択するとそのユーザーとのDMが可能になります。

![DM](https://github.com/junjiIshii/readmeImg/blob/master/tabi_picrue/directMail.png)

#### プロフィール編集
新規登録で入力した情報を編集することができます。他にもユーザー詳細画面で表示される __背景画像、ユーザーアイコン、自己紹介文を編集__ することができます。そして非公開情報で電話番号・住所・年齢などの登録もできます。これらの入力値にもバリデーションが適用されています。

![プロフィール編集](https://github.com/junjiIshii/readmeImg/blob/master/tabi_picrue/profileEdit.png)

#### 編集一覧、お気に入り一覧、フォロー管理
編集一覧では登録した商品を小さく表示します。どの商品を編集するかを選択したり、ボタン一つで簡単に非公開化・削除処理を行えます。お気に入り一覧では商品一覧などで「お気に入り登録」した商品を閲覧できます。フォロー管理ではフォローしたユーザーの管理ができます。フォローを外したり、フォロワーの確認ができます。


### 登録・ログイン
---

#### 新規登録
ユーザー名（他のユーザーに見せる名前）、ログイン用のEmailアドレス、ログイン用パスワードを設定してユーザー登録します。

- DB上でのEmail重複チェック
- Email形式チェク
- パスワードの最低文字数チェック
- 再入力と合っているかのチェック

以上のバリデーションを行いクリアした場合、新規ユーザーとして登録ができます。今回のサイトはポートフォリオ用の練習サイトなので、aaa@example.comのような適当なアドレス（@,ドメイン）があれば登録はできます。（ただしこちらからのパスワード変更などを伝えるメールは受信できません。）

![新規登録](https://github.com/junjiIshii/readmeImg/blob/master/tabi_picrue/register.png)

#### ログイン・ログアウト
新規登録で入力した、Emailとパスワードを入力して合致すればログインできます。このサイトではログイン処理にセッションを使用しています。ログアウトはヘッダーの「ログアウト」をクリックするだけでセッションが破棄され、ログアウト状態になります。


### その他の機能
---

#### パスワード救済措置
パスワードを忘れてしまった場合に登録したEmailを用いてパスワードの変更ができます。ログイン画面などで「パスワードを忘れた場合はこちら」をクリックすると変更用のページに移動します。Emailを入力するとそのアドレス宛に、「認証キー」と「キー確認画面のURL」が送信されます。30分以内に認証キーを確認画面で入力すると、パスワードを変更できます。一連の処理ではこちらのサーバーから連絡用のメールが送られます。

#### アカウント復活
退会した後、気が変わって改めて登録した時に同じ内容アドレスで登録した場合「以前退会したアカウント」を引き継ぐことができます。また同じアドレス・パスワードでログインした場合でも復活処理が実行されます。

#### 退会
退会をすると、ユーザーが登録した商品、プロフィール情報は表示されなくなり、また他のユーザーは閲覧することができなくなります。

---
---
## 開発詳細
開発言語はHTML/CSS,PHP,MySQL,jsを用いて主にそれらの知識の練習として作成しました。UI/UX・デザイン・実用性は最低限しかありませんがおかしな挙動なく動きます。ゲストユーザーとしてログインして、好きな写真をアップしてみたり、DMを送りあって遊んであげてください。旅専門となっているのは完全に私自身の趣味です。

### 開発目的・理由
このサイトを作った理由は __アウトプットを作り、プログラミング・Webサービス開発の理解を深めるため__ です。独学で書籍や現役Webエンジニアの兄からのアドバイスを元に学習を進めていました。しかしサーバー周りの知識、フロントエンドの知識はたまったものの、断片的であり全ての知識が繋がった状態ではありませんでした。そこで習った知識を全て復習し、体系的に習得し、新しい課題を見つけるためにこのサイトを作成しました。

そのため実用性やデザインも最低限しかありません。インスタやピクスタを利用していたので、旅の写真を取ることが好きなことも加え、写真共有・売買サイトを作ることにしました。しかし独特な機能はあまり思いつかなかったので、とりあえずインスタとピクスタをかけたようなサイトにしました。（売買機能とユーザー同士の関わり合いなど）

---
### 開発を通して得たもの
このサイト作成は __フレームワークを使用していません。__ そのため雑多なコードが多くなってしまいましたが、フレームワークを使用しなかった理由は __Webサービスの仕組み・原理を理解すること、バグの解決や開発詰まりを自力でどうにか解決すること__ を重視したからです。実際の開発ではスピード・保守性・チームワークの関係からフレームワークを使わず、今回のような感じで設計してコードを書けば先輩エンジニアは失神するでしょう。会員機能もフレームワークを使えば一発ですが、あえてセッションの条件やそのための関数を自分で一から作ることで、 __Webサービス上で実装されている機能はこのようにして成り立っているのか！__ という原理レベルでの理解ができました。

また予想外のバグ、挙動の解決をしているうちに、 __エラーの解決フロー、ブラウザの挙動やDOM・言語仕様などの理解を深められた__ のも大きな学びでした。


### 得られた課題
バラバラだった各知識の結びつけや、エラー対処の経験など得られましたが同時に課題も見つかりました。

- 保守性をいかに高める設計をしていくか
- デザインやアニメーションなどのUI/UXの知識
- フレームワークの重要性
- タスク管理
- CSS設計

特に保守性とフレームワークの重要性が１番の課題と思いました。開発を終えて、デバックしている時に改めてコードを眺めてみると「なんじゃこの分かりにくいコードは」と思うものが散在していました。ヘッダー・フッター、関数ファイルなどモジュール化できる部分はある程度モジュールにしましたが、他人に見せて自力で全体を把握できるかというと超微妙です。しかし課題の発見は同時に新しい知識や技術を使えるようにしようという勉強のモチベーションになったのは良いと思います。

---
### 工夫したこと
#### ゲストログイン機能
主にポートフォリオ閲覧者用のものですが、ユーザー名やパスワードを入力せずともログインできるようにしました。

#### フォロー、DM機能
このサイトでは写真をただ売買するだけでなく、自分好みの写真をUPしてくれる人、同じ趣向を持った人と繋がれるようにフォロー、DM機能を実装しました。実装は難しいように感じましたが、DBでそれぞれのユーザーIDを照らし合わせるだけだったので意外とすんなり実装できました。フォローは気軽にできるように、Ajaxを用いてページ遷移なくフォローとその解除をできるようにしました。

---
### 苦労した点・足りなかった点
#### デプロイ
ローカルから公表用のサーバーに移動して見てみると、なんということでしょう！ローカルでは見られなかったCSSの謎ズレやPHPのバグがあるではありませんか！多分ローカルでのテストで見逃したのかもしれませんが、サーバーにアップするときの手間や、DBの移動など「ちゃんとUPできているかな。。？」という心配にかられたり、対処が大変でした。

#### 予想だにしない挙動
処理は走るが、こちらが求める仕様と違う！ログアウトしたのにログインした状態になっていたり、DMの表示順がバラバラになったりなど、「フォルト」の対処が一番苦労しました。まだエラー画面で処理が止まってくれ方がよかったです。デバッグでちゃんと仕様通りに動くか、またいろんなパターンを試してみてテストをしましたが、そのテスト量や漏れがあとで見つかったときの対処が大変でした。

#### レスポンシブデザイン
多くのページ全てにレスポンシブ用のCSSを適用する時に、既存のHTMLがPCファーストで設計してしまったのでそこからのCSS設計に苦労しました。ちょっと雑になってしまったとこもありました。もう少しJSの知識を積んで要素の構造を操作したり、やっぱりフレームワークで管理した方が効率がいいなと思いました。

---
---

## 全体的な感想
おおよそ80時間ほどかけて動く形として実装はできましたが、プログラマー目線・サービス運営目線でみた場合はまだまだ至らない点は多くあります。作業スピード、全体的な設計、フレームワークの重要性、CSS設計、など新しい課題が得られました。このポートフォリオを作成した後も、新しい学習を進め「この技術や考えのもと、こう改善すればいいんじゃないか？」と考えることに出会うようになりました。

学習進行度の証明、より深くプログラミング・Webサービス開発をしれたこと、課題の発見など得られたものは多かったので非常にタメになったアウトプットだと思いました。
