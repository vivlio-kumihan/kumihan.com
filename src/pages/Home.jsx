import { useState, useRef } from "react";
import { ImageSwiper } from "../components/ui/MySwiper";
import Movie from "../components/ui/Movie";
import { BaseButton, MovieControlButton } from "../components/ui/Button";
import styles from "./Home.module.scss";

const Home = () => {
  const myImages = [
    { id: 1, src: "/images/210712_G9_1130904.jpg", caption: "鉄塔と光" },
    { id: 2, src: "/images/210806_G9_1130937.jpg", caption: "丸い雲" },
    { id: 3, src: "/images/210806_G9_1130958.jpg", caption: "東山の夕景" },
  ];

  // const [playing, setPlaying] = useState(false);
  // const switchHandler = () => {
  //   setPlaying((prevState) => !prevState);
  // };
  const ref = useRef();

  return (
    <>
      <div className="container">
        <div className="wrapper">
          <h1>
            <span className={styles.homeSpan}>
              You say Good Luck, I say Hello...
            </span>
            Studio Quad9
          </h1>
          <div className={`catch ${styles.catch}`}>
            こんにちは、スタジオ・クアッド9のWEBサイトへようこそ。
          </div>
        </div>
        <ImageSwiper
          useFade
          autoplay={{ delay: 3000 }}
          speed={3000}
          images={myImages}
          heightMqLg={"500px"}
        />
        <Movie ref={ref} path="/movies/sample.mov" />
        <div className={`wrapper ${styles.btn}`}>
          <MovieControlButton ref={ref}>
            <div className="note">hello, hello, hello</div>
          </MovieControlButton>
          <BaseButton>Base Button</BaseButton>
        </div>
      </div>
    </>
  );
};

export default Home;
