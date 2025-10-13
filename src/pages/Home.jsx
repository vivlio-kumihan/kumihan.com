import { useState, useRef } from "react";
import useScrollAnimation from "../hooks/useScrollAnimation";
import { ImageSwiper } from "../components/ui/MySwiper";
import Gallery from "../components/ui/Gallery";
import Movie from "../components/ui/Movie";
import { BaseButton, MovieControlButton } from "../components/ui/Button";
import styles from "./Home.module.scss";

const Home = () => {
  const myImages = [
    { id: 1, src: "/images/210712_G9_1130904.jpg", caption: "鉄塔と光" },
    { id: 2, src: "/images/210806_G9_1130937.jpg", caption: "丸い雲" },
    { id: 3, src: "/images/210806_G9_1130958.jpg", caption: "東山の夕景" },
  ];

  const [playing, setPlaying] = useState(false);
  const switchHandler = () => {
    setPlaying((prevState) => !prevState);
  };
  const ref = useRef();

  // スクロールアニメーションを有効化 (この1行だけ追加!)
  useScrollAnimation({
    parentSelector: ".appear",
    childSelector: ".up",
    threshold: 0.4,
    stagger: 150, // 各要素の表示遅延(ms)
  });

  return (
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
      <div className={`wrapper ${styles.home} appear`}>
        <h2 className="up">Heading2-1</h2>
        <p className="up">
          このときにはこうなのだあの声、ぼくなんべんもお母さんから聞いたわ。では今日はその銀河の水はちらちら小さな波をたてたり、虹のように足をのばしていましたが、まもなく二人は、そのきれいな野原じゅうの青や橙や、いろいろなふうになりました。ぼくは学校から帰る途中たびたびカムパネルラのうちへもつれて行ったよ。わたしはずうっとぐあいがいいよジョバンニはまったくその大きな火の向こうに三つの三角標の列は、けむるように燃えるように見えました。そして見ているというように、すっときれいにはなれました。
        </p>
        <h2 className="up">Heading2-2</h2>
        <div className="img-wrapper up">
          <img src="/images/210806_G9_1130937.jpg" alt="夕景の近所" />
        </div>
        <p className="up">
          するともう鷺は、蛍のように、尋ねました。中で小さな火が燃えているのでしたが、立って荷物をとったような二つの窓には日覆いがおりたままになってしずかにくるくるとまわっていました。川まではよほどありましょうかねえええ、ええ、もうこの辺はひどい高原ですからうしろの方に不思議なものをひろいました。さがすと証拠もぞくぞく出ているのです。いまとって来た方を、窓から外を見てくすっとわらいましたので、なんだかわかりません。
        </p>
        <h2 className="up">Heading2-3</h2>
        <p className="up">
          ただたくさんのくるみの木が幾本も幾本も、高く星ぞらに浮かんでいるのです。ぼくらからみると、ここはランカシャイヤだ。そしてカムパネルラもまた、高く口笛を吹いたり笑ったりしていました。こどもが水へ落ちたんですかいや、証明するに要るんだ。きっとみんなのほんとうの神さまうその神さまだいあなたの神さまはもちろんたった一人ですああ、そうだ、ぼくの方をちょっと見ました。
        </p>
        <h2 className="up">Heading2-1</h2>
        <p className="up">
          このときにはこうなのだあの声、ぼくなんべんもお母さんから聞いたわ。では今日はその銀河の水はちらちら小さな波をたてたり、虹のように足をのばしていましたが、まもなく二人は、そのきれいな野原じゅうの青や橙や、いろいろなふうになりました。ぼくは学校から帰る途中たびたびカムパネルラのうちへもつれて行ったよ。わたしはずうっとぐあいがいいよジョバンニはまったくその大きな火の向こうに三つの三角標の列は、けむるように燃えるように見えました。そして見ているというように、すっときれいにはなれました。
        </p>
        <h2 className="up">Heading2-2</h2>
        <div className="img-wrapper up">
          <img src="/images/210806_G9_1130937.jpg" alt="夕景の近所" />
        </div>
        <p className="up">
          するともう鷺は、蛍のように、尋ねました。中で小さな火が燃えているのでしたが、立って荷物をとったような二つの窓には日覆いがおりたままになってしずかにくるくるとまわっていました。川まではよほどありましょうかねえええ、ええ、もうこの辺はひどい高原ですからうしろの方に不思議なものをひろいました。さがすと証拠もぞくぞく出ているのです。いまとって来た方を、窓から外を見てくすっとわらいましたので、なんだかわかりません。
        </p>
        <h2 className="up">Heading2-3</h2>
        <p className="up">
          ただたくさんのくるみの木が幾本も幾本も、高く星ぞらに浮かんでいるのです。ぼくらからみると、ここはランカシャイヤだ。そしてカムパネルラもまた、高く口笛を吹いたり笑ったりしていました。こどもが水へ落ちたんですかいや、証明するに要るんだ。きっとみんなのほんとうの神さまうその神さまだいあなたの神さまはもちろんたった一人ですああ、そうだ、ぼくの方をちょっと見ました。
        </p>
        <h2 className="up">Heading2-1</h2>
        <p className="up">
          このときにはこうなのだあの声、ぼくなんべんもお母さんから聞いたわ。では今日はその銀河の水はちらちら小さな波をたてたり、虹のように足をのばしていましたが、まもなく二人は、そのきれいな野原じゅうの青や橙や、いろいろなふうになりました。ぼくは学校から帰る途中たびたびカムパネルラのうちへもつれて行ったよ。わたしはずうっとぐあいがいいよジョバンニはまったくその大きな火の向こうに三つの三角標の列は、けむるように燃えるように見えました。そして見ているというように、すっときれいにはなれました。
        </p>
        <h2 className="up">Heading2-2</h2>
        <div className="img-wrapper up">
          <img src="/images/210806_G9_1130937.jpg" alt="夕景の近所" />
        </div>
        <p className="up">
          するともう鷺は、蛍のように、尋ねました。中で小さな火が燃えているのでしたが、立って荷物をとったような二つの窓には日覆いがおりたままになってしずかにくるくるとまわっていました。川まではよほどありましょうかねえええ、ええ、もうこの辺はひどい高原ですからうしろの方に不思議なものをひろいました。さがすと証拠もぞくぞく出ているのです。いまとって来た方を、窓から外を見てくすっとわらいましたので、なんだかわかりません。
        </p>
        <h2 className="up">Heading2-3</h2>
        <p className="up">
          ただたくさんのくるみの木が幾本も幾本も、高く星ぞらに浮かんでいるのです。ぼくらからみると、ここはランカシャイヤだ。そしてカムパネルラもまた、高く口笛を吹いたり笑ったりしていました。こどもが水へ落ちたんですかいや、証明するに要るんだ。きっとみんなのほんとうの神さまうその神さまだいあなたの神さまはもちろんたった一人ですああ、そうだ、ぼくの方をちょっと見ました。
        </p>
        <h2 className="up">Heading2-1</h2>
        <p className="up">
          このときにはこうなのだあの声、ぼくなんべんもお母さんから聞いたわ。では今日はその銀河の水はちらちら小さな波をたてたり、虹のように足をのばしていましたが、まもなく二人は、そのきれいな野原じゅうの青や橙や、いろいろなふうになりました。ぼくは学校から帰る途中たびたびカムパネルラのうちへもつれて行ったよ。わたしはずうっとぐあいがいいよジョバンニはまったくその大きな火の向こうに三つの三角標の列は、けむるように燃えるように見えました。そして見ているというように、すっときれいにはなれました。
        </p>
        <h2 className="up">Heading2-2</h2>
        <div className="img-wrapper up">
          <img src="/images/210806_G9_1130937.jpg" alt="夕景の近所" />
        </div>
        <p className="up">
          するともう鷺は、蛍のように、尋ねました。中で小さな火が燃えているのでしたが、立って荷物をとったような二つの窓には日覆いがおりたままになってしずかにくるくるとまわっていました。川まではよほどありましょうかねえええ、ええ、もうこの辺はひどい高原ですからうしろの方に不思議なものをひろいました。さがすと証拠もぞくぞく出ているのです。いまとって来た方を、窓から外を見てくすっとわらいましたので、なんだかわかりません。
        </p>
        <h2 className="up">Heading2-3</h2>
        <p className="up">
          ただたくさんのくるみの木が幾本も幾本も、高く星ぞらに浮かんでいるのです。ぼくらからみると、ここはランカシャイヤだ。そしてカムパネルラもまた、高く口笛を吹いたり笑ったりしていました。こどもが水へ落ちたんですかいや、証明するに要るんだ。きっとみんなのほんとうの神さまうその神さまだいあなたの神さまはもちろんたった一人ですああ、そうだ、ぼくの方をちょっと見ました。
        </p>
      </div>
      <Gallery />
      <Movie ref={ref} path="/movies/sample.mp4" />
      <div className={`wrapper ${styles.btn}`}>
        <MovieControlButton ref={ref}>
          <div className="note">hello, hello, hello</div>
        </MovieControlButton>
        <BaseButton>Base Button</BaseButton>
      </div>
    </div>
  );
};

export default Home;

