import { useState, useEffect } from "react";
import styles from "./Header.module.scss";

const Header = () => {
  const [isVisivle, setIsVisible] = useState(true);
  const [lastScrollY, setLastScrollY] = useState(0);

  useEffect(() => {
    const handleScroll = () => {
      const currentScrollY = window.scrollY;
      currentScrollY > lastScrollY && currentScrollY > 80
        ? setIsVisible(false)
        : setIsVisible(true);
      setLastScrollY(currentScrollY);
    };

    window.addEventListener("scroll", handleScroll);
  
    return () => {
      window.removeEventListener("scroll", handleScroll);
    };
  }, [lastScrollY]);

  return (
    <header 
      className={styles.header}
      style={{transform: isVisivle ? "translate(0)" : "translateY(-100%)"}} 
    >
      <div className={styles.container}>
        <h1 className={styles.logo}>Studio Quad9</h1>
        <nav className={styles.nav}>
          <a href="/">Home</a>
          <a href="/about">About</a>
          <a href="/contact">Contact</a>
        </nav>
      </div>
    </header>
  );
};

export default Header;
