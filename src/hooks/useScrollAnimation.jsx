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
function useScrollAnimation({
  parentSelector = ".appear",
  childSelector = ".up",
  threshold = 0.1,
  direction = "bottom",
} = {}) {
  useEffect(() => {
    // .appear クラス内のすべての .up 要素を取得
    const parents = document.querySelectorAll(parentSelector);

    if (parents.length === 0) return;

    // すべての .up 要素を配列に集める
    const allTargets = [];

    parents.forEach((parent) => {
      const children = parent.querySelectorAll(childSelector);
      children.forEach((child) => {
        allTargets.push(child);
      });
    });

    if (allTargets.length === 0) return;

    // アニメーション方向によって初期transformを設定
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

    // 初期状態: すべての .up 要素を非表示にする
    allTargets.forEach((target) => {
      target.style.opacity = "0";
      target.style.transform = getInitialTransform();
      target.style.transition =
        "opacity 0.8s ease-out, transform 0.8s ease-out";
    });

    // 各 .up 要素を個別に監視
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

    // すべての .up 要素を個別に監視対象に追加
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