import { useState, useEffect } from 'react';
import styles from "./Contact.module.scss";
import { BaseButton } from "../components/ui/Button";

const Contact = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    message: ''
  });
  const [status, setStatus] = useState('idle'); // idle, sending, success, error
  const [errors, setErrors] = useState({});

  // バリデーション
  const validate = () => {
    const newErrors = {};
    
    if (!formData.name.trim()) {
      newErrors.name = 'お名前を入力してください';
    }
    
    if (!formData.email.trim()) {
      newErrors.email = 'メールアドレスを入力してください';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
      newErrors.email = '有効なメールアドレスを入力してください';
    }
    
    if (!formData.message.trim()) {
      newErrors.message = 'メッセージを入力してください';
    }
    
    return newErrors;
  };

  // フォーム送信
  const handleSubmit = async (e) => {
    e.preventDefault();
    
    const validationErrors = validate();
    if (Object.keys(validationErrors).length > 0) {
      setErrors(validationErrors);
      return;
    }
    
    setErrors({});
    setStatus('sending');

    // 実際のAPI送信(ここではダミー)
    // 実務では fetch() で Formspree や EmailJS、自社APIに送信
    try {
      await new Promise(resolve => setTimeout(resolve, 1500)); // 送信をシミュレート
      
      // 成功時の処理
      setStatus('success');
      setFormData({ name: '', email: '', message: '' });
    } catch (error) {
      setStatus('error');
    }
  };

  // 成功メッセージを3秒後に自動で消す
  useEffect(() => {
    if (status === 'success') {
      const timer = setTimeout(() => {
        setStatus('idle');
      }, 3000);

      // クリーンアップ: タイマーをクリア
      return () => clearTimeout(timer);
    }
  }, [status]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
    // 入力中にエラーをクリア
    if (errors[name]) {
      setErrors(prev => ({
        ...prev,
        [name]: ''
      }));
    }
  };

  return (
    <div className={`container ${styles.contact}`}>
      <h1>Contact Page</h1>
      <div className="wrapper">
        <h2 className={styles.contact__heading2}>お問い合わせ</h2>
        <form onSubmit={handleSubmit}>
          {/* お名前 */}
          <div className={styles.contact__formInner}>
            <label className={styles.contact__lavel}>
              お名前 <span>*</span>
            </label>
            <input
              className={styles.contact__input}
              type="text"
              name="name"
              value={formData.name}
              onChange={handleChange}
              style={{
                border: errors.name ? "2px solid #e53e3e" : "1px solid #ddd",
              }}
              placeholder="山田太郎"
            />
            {errors.name && (
              <div className={styles.contact__warning}>{errors.name}</div>
            )}
          </div>

          {/* メールアドレス */}
          <div className={styles.contact__formInner}>
            <label className={styles.contact__lavel}>
              メールアドレス <span style={{ color: "red" }}>*</span>
            </label>
            <input
              className={styles.contact__input}
              type="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              style={{
                border: errors.email ? "2px solid #e53e3e" : "1px solid #ddd",
              }}
              placeholder="example@email.com"
            />
            {errors.email && (
              <div className={styles.contact__warning}>{errors.email}</div>
            )}
          </div>

          {/* メッセージ */}
          <div style={{ marginBottom: "1.5rem" }}>
            <label className={styles.contact__formInner}>
              メッセージ <span style={{ color: "red" }}>*</span>
            </label>
            <textarea
              className={styles.contact__input}
              name="message"
              value={formData.message}
              onChange={handleChange}
              rows="6"
              placeholder="お問い合わせ内容をご記入ください"
            />
            {errors.message && (
              <div className={styles.contact__warning}>{errors.message}</div>
            )}
          </div>

          {/* 送信ボタン */}
          <BaseButton
            className={styles.contact__btn}
            type="submit"
            disabled={status === "sending"}
          >
            {status === "sending" ? "送信中..." : "送信する"}
          </BaseButton>

          {/* 成功メッセージ */}
          {status === "success" && (
            <div className={styles.contact__message}>✓ 送信が完了しました!</div>
          )}

          {/* エラーメッセージ */}
          {status === "error" && (
            <div className={`${styles.contact__message} ${styles.error}`}>
              送信に失敗しました。もう一度お試しください。
            </div>
          )}
        </form>
      </div>
    </div>
  );
}

export default Contact;

// <button
//   type="submit"
//   disabled={status === "sending"}
//   style={{
//     width: "100%",
//     padding: "1rem",
//     fontSize: "1rem",
//     fontWeight: "bold",
//     color: "white",
//     backgroundColor: status === "sending" ? "#a0aec0" : "#3182ce",
//     border: "none",
//     borderRadius: "4px",
//     cursor: status === "sending" ? "not-allowed" : "pointer",
//     transition: "background-color 0.2s",
//   }}
//   onMouseOver={(e) => {
//     if (status !== "sending") {
//       e.target.style.backgroundColor = "#2c5282";
//     }
//   }}
//   onMouseOut={(e) => {
//     if (status !== "sending") {
//       e.target.style.backgroundColor = "#3182ce";
//     }
//   }}
// >
//   {status === "sending" ? "送信中..." : "送信する"}
// </button>;