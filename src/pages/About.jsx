import { react } from "react";
import styles from "./About.module.scss";
import { ImageSwiper, GraphicSwiper } from "../components/ui/MySwiper";

const About = () => {
  const myImages = [
    { id: 1, src: "/images/210712_G9_1130904.jpg", caption: "鉄塔と光" },
    { id: 2, src: "/images/210806_G9_1130937.jpg", caption: "丸い雲" },
    { id: 3, src: "/images/210806_G9_1130958.jpg", caption: "東山の夕景" },
  ];

  const myGraphics = [
    { id: 1, title: "プロジェクト1", text: "説明文", color: "#ff6b6b" },
    { id: 2, title: "プロジェクト2", text: "説明文", color: "#4ecdc4" },
  ];

  return (
    <>
      <div className="container">
        <h1 className={styles.aboutHeading1}>Slider Test</h1>
        <div className="wrapper">
          {/* デフォルトデータで表示 */}
          <ImageSwiper />

          {/* Swiperの設定もカスタマイズ可能 */}
          <ImageSwiper autoplay={{ delay: 5000 }} />

          {/* カスタムデータで表示 */}
          <ImageSwiper images={myImages} />

          {/* GraphicSwiperも同様 */}
          <GraphicSwiper slides={myGraphics} />
        </div>
      </div>
    </>
  );
};

export default About;