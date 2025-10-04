import styles from "./Footer.module.scss";

const Footer = () => {
  return (
    <footer className={styles.footer}>
      <p className={styles.copyright}>© 2025 My Site. All rights reserved.</p>
    </footer>
  );
};

export default Footer;