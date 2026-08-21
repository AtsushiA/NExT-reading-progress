*日本語 | [English](README.en.md)*

# NExT Reading Progress

ページ全体のスクロール進捗を表示するリーディングプログレスバーを提供する、WordPress用のGutenbergブロックプラグインです。

## 特徴

- **動的ブロック**（`next/reading-progress`）として実装。設置した場所にそのまま表示されます（`position: fixed`等の固定表示は行わないため、固定したい場合はテーマ側のテンプレートで対応してください）
- 進捗はページ全体（ヘッダー・フッターを含む）のスクロール量を基準に算出します
- 投稿・固定ページ・テンプレートパーツなど、ブロックを挿入できる場所であればどこでも利用可能
- 同一ページに複数配置しても進捗値は同期します
- スクロール検知・DOM更新は [WordPress Interactivity API](https://developer.wordpress.org/block-editor/reference-guides/interactivity-api/) を使用

### 設定できる項目

| 項目 | 内容 |
| --- | --- |
| 幅 | ブロックの標準配置（コンテンツ幅/幅広/全幅）と併用可能な数値指定（px, %, em, rem, vw） |
| バーの太さ | 数値指定（px, em, rem） |
| 進捗バーの色 | 単色 または グラデーション |
| 未読部分（トラック）の色 | 単色 |
| z-index | 数値（任意設定） |

`role="progressbar"` / `aria-valuemin` / `aria-valuemax` / `aria-valuenow` を付与し、進捗値はスクロールに応じてリアクティブに更新されます。

## 動作要件

- WordPress 6.8 以上
- PHP 7.4 以上

## インストール

### リリース版を使う場合

1. `npm run plugin-zip` で生成される zip ファイル、またはビルド済みの本プラグインディレクトリ一式を `wp-content/plugins/` 配下に配置します
2. WordPress管理画面の「プラグイン」から **NExT Reading Progress** を有効化します

### ソースからビルドする場合

```bash
cd wp-content/plugins/NExT-reading-progress
npm install
npm run build
```

`src/` 以下のソースが `build/` にビルドされます。プラグイン本体（`next-reading-progress.php`）は `build/` を読み込みます。

## 使い方

1. ブロックエディタでブロック追加ボタンから「Reading Progress Bar」を検索して挿入します
2. サイドバーのブロック設定（インスペクターコントロール）から、幅・バーの太さ・カラー（進捗バー/トラック）・z-indexを調整します
3. ページ最上部などテンプレートパーツ内に配置する場合は、Site Editor側のテンプレート/テンプレートパーツにブロックを挿入してください

## 開発

```bash
npm start          # 開発用ビルド（watch）
npm run build       # 本番用ビルド
npm run format       # コード整形
npm run lint:js      # JSのLint
npm run lint:css     # CSSのLint
npm run plugin-zip   # 配布用zipの生成
```

### ディレクトリ構成

```
NExT-reading-progress/
├── next-reading-progress.php   # プラグイン本体（ブロック登録）
├── src/reading-progress/       # ブロックソース
│   ├── block.json
│   ├── edit.js                 # エディタ側UI
│   ├── render.php              # サーバーサイドレンダリング
│   ├── view.js                 # Interactivity APIストア（フロント用）
│   ├── style.scss / editor.scss
│   └── index.js
├── build/                      # ビルド成果物（プラグインが読み込む実体）
└── SPEC.md                     # 仕様
```

## ライセンス

[GPL-2.0-or-later](https://www.gnu.org/licenses/gpl-2.0.html)
