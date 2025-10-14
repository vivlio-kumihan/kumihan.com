# Site Map

```
├── src/
│   ├── main.jsx
│   ├── App.jsx
│   ├── components/
│   │   ├── layout/
│   │   │   ├── Header.jsx
│   │   │   ├── Header.module.scss
│   │   │   ├── Footer.jsx
│   │   │   ├── Footer.module.scss
│   │   │   ├── Layout.jsx
│   │   └── ui/
│   │       └── MediaQuerry.jsx
│   │       └── Button.jsx (styled-components)
│   │       └── MySwiper.jsx (styled-components)
│   │       └── Movie.jsx
│   │       └── Gallery.jsx (styled-components)
│   │       └── GalleryData.jsx
│   ├── hooks/
│   │   ├── useScrollAnimation.jsx
│   ├── pages/
│   │   ├── Home.jsx
│   │   ├── Home.module.scss
│   │   ├── About.jsx
│   │   ├── About.module.scss
│   │   ├── Contact.jsx
│   │   └── Contact.module.scss
│   ├── styles/
│   │   ├── main.scss
│   │   └── globals
│   │       ├── _index.scss
│   │       ├── _variables.scss
│   │       ├── _mixins.scss
│   │       └── _reset.scss
│   └── assets/
└── public/
    ├── images/
    │   └── gallery/
    │       └── index.jsx
    │       └── photo01.png
    │       └── photo02.jpg
    │       └── photo03.jpg
    │       └── ...
    └── movies/       
```


# React + Vite

This template provides a minimal setup to get React working in Vite with HMR and some ESLint rules.

Currently, two official plugins are available:

- [@vitejs/plugin-react](https://github.com/vitejs/vite-plugin-react/blob/main/packages/plugin-react) uses [Babel](https://babeljs.io/) (or [oxc](https://oxc.rs) when used in [rolldown-vite](https://vite.dev/guide/rolldown)) for Fast Refresh
- [@vitejs/plugin-react-swc](https://github.com/vitejs/vite-plugin-react/blob/main/packages/plugin-react-swc) uses [SWC](https://swc.rs/) for Fast Refresh

## React Compiler

The React Compiler is not enabled on this template because of its impact on dev & build performances. To add it, see [this documentation](https://react.dev/learn/react-compiler/installation).

## Expanding the ESLint configuration

If you are developing a production application, we recommend using TypeScript with type-aware lint rules enabled. Check out the [TS template](https://github.com/vitejs/vite/tree/main/packages/create-vite/template-react-ts) for information on how to integrate TypeScript and [`typescript-eslint`](https://typescript-eslint.io) in your project.

## ルーティングが効かなくなる問題
Apacheのレンタルサーバーですね。`.htaccess`ファイルで設定します。

## 解決方法

### 1. `.htaccess`ファイルを作成

プロジェクトの`public`フォルダに`.htaccess`ファイルを作成してください：

```apache
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  RewriteRule ^index\.html$ - [L]
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-l
  RewriteRule . /index.html [L]
</IfModule>
```

### 2. ビルドして再デプロイ

```bash
npm run build
```

`dist`フォルダの中身（`.htaccess`を含む）を全てサーバーにアップロードしてください。

## ディレクトリ構成

```
public/
  └── .htaccess  ← ここに作成

↓ ビルド後

dist/
  ├── index.html
  ├── .htaccess  ← 自動的にコピーされる
  ├── assets/
  └── ...
```

## サブディレクトリにデプロイする場合

もし`https://example.com/studio/`のようにサブディレクトリにデプロイする場合：

### `.htaccess`を修正

```apache
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /studio/  # ← サブディレクトリ名に変更
  RewriteRule ^index\.html$ - [L]
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-l
  RewriteRule . /studio/index.html [L]  # ← ここも変更
</IfModule>
```

### `vite.config.js`も修正

```javascript
// vite.config.js
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [react()],
  base: '/studio/',  // ← サブディレクトリを指定
})
```

### React Routerも修正

```jsx
// main.jsx または App.jsx
import { BrowserRouter } from 'react-router-dom';

ReactDOM.createRoot(document.getElementById('root')).render(
  <BrowserRouter basename="/studio">  {/* ← basename追加 */}
    <App />
  </BrowserRouter>
);
```

## 確認方法

1. `.htaccess`がアップロードされているか確認
2. ブラウザで直接`https://example.com/about`にアクセス
3. 404エラーが出ずにAboutページが表示されればOK

## それでも動かない場合

### チェックポイント：

1. **mod_rewriteが有効か確認**
   - レンタルサーバーの管理画面で確認
   - または問い合わせてみる

2. **.htaccessが読み込まれているか**
   - わざとエラーを起こして確認：
   ```apache
   InvalidDirective test
   ```
   500エラーが出れば読み込まれています

3. **ファイルが正しくアップロードされているか**
   - 隠しファイル（`.`で始まるファイル）が見えない場合がある
   - FTPクライアントの設定で「隠しファイルを表示」を有効に

どうしても動かない場合は、レンタルサーバーの名前（さくら、ロリポップ、エックスサーバーなど）を教えてください。サービス固有の設定をお伝えします！


伝えた構成が間違ってた。
│   ├── styles/
│   │   ├── main.scss
│   │   └── globals
│   │       ├── _variables.scss
│   │       ├── _mixins.scss
│   │       └── _reset.scss

そして、main.scssの内容が以下だ。

@use './globals' as *;

* {
  box-sizing: border-box;
  padding: 0;
}

html {
  font-size: 100%;
  scroll-behavior: smooth;
}

body {
  font-family: $font-gothic;
  line-height: $lh-normal;
  color: $text-color;
}

.content-width {
  margin: 0 auto;
  max-width: $content-max-width;
  @include mq(md) {
    width: 90%;
  }                                     
}

.container {
  margin-top: 85px;
}

h1, h2, h3, h4, h5, h6 {
  @extend.tume;  
  line-height: $lh-tight;
}

h1 {
  text-align: center;
  font-family: $font-title-en;
  font-size: 6em;
  font-weight: 200;
  line-height: 1;
  color: rgba($color: #333, $alpha: .6);
}

a {
  text-decoration: none;
}

.wrapper {
  margin: 2em 0;
}

.img-wrapper {
  margin: 2rem 0;
  aspect-ratio: 16/9;
  & > img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}

.catch {
  @extend.tume;
  font-size: 1.1rem;
  line-height: 1.2;
}

