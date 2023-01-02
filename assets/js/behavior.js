// for ハンバーガーボタン
// toggleで効果のかかりをスイッチング
const menuBtn = document.getElementById("menu-button"),
  menu = document.getElementById("links-menu")
coverBg = document.getElementById("cover-background")
menuBtn.addEventListener("click", () => {
  menuBtn.classList.toggle("active")
  menu.classList.toggle("active")
  coverBg.classList.toggle("active")
})

// for リンクメニューのリスト
const parentMenu = document.querySelectorAll("#parent-link-list > li > a")
console.log(parentMenu)


parentMenu.forEach((element, idx) => {
  element.addEventListener("click", (tagEvent) => {
    // リンクをクリックすると子供のタグがすぐに引っ込む現象について、
    // 無名関数の引数にイベント
    // そのインスタンスへpreventDefault()関数を充てると、
    // <a href=""></a> => <a></a>扱いにすることで回避する。
    tagEvent.preventDefault()
    // 指定したアンカーの同列次の要素のクラスをtoggleする。
    element.nextElementSibling.classList.toggle("active")
  })
});

const swiper = new Swiper('.swiper', {
  // Optional parameters
  // 自動でループ挿せる際に、最後のスライドから最初へ遷移でバグがある。
  // それの回避方法が『loop: true』
  loop: true,
  effect: 'fade',
  // どれくらいの時間をかけて切り替わるかを調整する。
  speed: 2000,
  // 自動で再生
  autoplay: {
    delay: 1000,
    disableOnInteraction: false,
  },

  // ページネーションをクリックして該当写真まで移動
  pagination: {
    el: '.swiper-pagination',
    clickable: true
  },

  // ナビゲーションのタブを表示
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  }
})