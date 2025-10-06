import { react } from "react";
// import MySwiper from "../components/ui/MySwiper";
import styles from "./Home.module.scss";

const Home = () => {
  return (
    <>
      <div className="container">
        <div className="wrapper">
          <h1>
            <span>You say Good Luck, I say Hello...</span>
            Studio Quad9
          </h1>
          <div className={`catch ${styles.catch}`}>こんにちは、スタジオ・クアッド9のWEBサイトへようこそ。</div>
        </div>
        {/* <MySwiper /> */}
      </div>
    </>
  );
};

export default Home;
