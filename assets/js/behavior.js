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