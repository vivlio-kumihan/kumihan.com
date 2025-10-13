// src/hooks/useScrollAnimation.js
import { useEffect } from "react";

/**
 * スクロールアニメーション用カスタムフック
 * .appear 内の各 .up 要素を個別に監視し、画面に入るたびにアニメーション
 *
 * @param {string} parentSelector - 親要素のセレクタ (デフォルト: '.appear')
 * @param {string} childSelector - 子要素のセレクタ (デフォルト: '.up')
 * @param {number} threshold - 表示トリガーの閾値 (デフォルト: 0.1)
 * @param {string} direction - アニメーション方向 ('bottom' | 'left' | 'right' | 'top')
 */

// hook作成
const useScrollAnimation = ({
  parentSelector = ".appear",
  childSelector = ".up",
  threshold = 0.1,
  direction = "bottom",
} = {}) => {
  useEffect(() => {
    // アニメーションの対象の収集
    //   .appearクラスを収集する。
    const parents = document.querySelectorAll(parentSelector);
    //   要素がなければ処理の終了。
    if (parents.length === 0) return;
    //   アニメーションの対象となる全ての.upクラスを収集する。
    const allTargets = Array.from(parents).flatMap((parent) => {
      return Array.from(parent.querySelectorAll(childSelector));
    });
    //   対象がなければ処理を終了する。
    if (allTargets.length === 0) return;

    // アニメーション方向によって初期transformを操作する関数を設定
    const getInitialTransform = () => {
      switch (direction) {
        case "left":
          return "translateX(-30px)";
        case "right":
          return "translateX(30px)";
        case "top":
          return "translateY(-30px)";
        case "bottom":
        default:
          return "translateY(30px)";
      }
    };

    // 初期状態: すべての .up 要素のopacity, transform, transitionを設定する。
    allTargets.forEach((target) => {
      target.style.opacity = "0";
      target.style.transform = getInitialTransform();
      target.style.transition =
        "opacity 0.8s ease-out, transform 0.8s ease-out";
    });

    // IntersectionObserverクラスの定義
    // 各.upクラスをどのように監視するか定義している
    //   第1引数では、監視区域に入った時のスタイル値とオプション
    //   第2引数では、画面のどのラインからとそのラインからどれくらい手前で発火するのかを指定
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            // 画面に入ったら表示
            entry.target.style.opacity = "1";
            entry.target.style.transform = "translate(0, 0)";

            // 一度表示されたら監視を停止(パフォーマンス向上)
            observer.unobserve(entry.target);
          }
        });
      },
      {
        threshold: threshold,
        rootMargin: "0px 0px -50px 0px", // 下から50px手前で発火
      }
    );

    // すべての.up要素へ個別に効果を与える。
    // インスタンスobserverにメソッドを送信、引数には対象となる要素。
    allTargets.forEach((target) => {
      observer.observe(target);
    });

    // クリーンアップ
    return () => {
      allTargets.forEach((target) => {
        observer.unobserve(target);
      });
    };
  }, [parentSelector, childSelector, threshold, direction]);
}

export default useScrollAnimation;
