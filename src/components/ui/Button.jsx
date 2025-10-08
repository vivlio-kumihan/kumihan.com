import { useState } from "react";
import styled from "styled-components";
import { mq } from "./MediaQuerry";

const StyledBaseButton = styled.button`
  /* レイアウト */
  display: inline-flex;
  justify-content: center;
  align-items: center;
  min-width: 120px;
  height: 44px;
  padding: 0 24px;
  /* タイポグラフィ */
  font-size: 14px;
  font-weight: 600;
  line-height: 1;
  white-space: nowrap;
  /* カラー */
  color: #ffffff;
  background-color: ${({ $isClicked }) => ($isClicked ? "#cc6f1dff" : "#ff9800")};
  /* ボーダー・角丸 */
  border: none;
  border-radius: 8px;
  /* インタラクション */
  cursor: pointer;
  user-select: none;
  /* アニメーション */
  transition: all 0.2s ease-in-out;
  /* 状態管理 */
  ${({ $isClicked }) => $isClicked && `box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);`}
  /* ホバー */
  &:hover {
    background-color: ${({ $isClicked }) => $isClicked ? "#d35400" : "#fb8c00"};
    box-shadow: 0 2px 8px rgba(255, 152, 0, 0.3);
    transform: translateY(-1px);
  }
  /* アクティブ */
  &:active {
    transform: translateY(0);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
  }
  /* フォーカス */
  &:focus-visible {
    outline: 2px solid #ff9800;
    outline-offset: 2px;
  }
  /* 無効状態 */
  &:disabled {
    background-color: #e0e0e0;
    color: #9e9e9e;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
  }
  /* レスポンシブ */
  ${mq.lg} {
    min-width: 140px;
    height: 48px;
    font-size: 15px;
    padding: 0 28px;
  }
`;

const StyledMovieControlButton = styled(StyledBaseButton)`
`;

export const BaseButton = ({ children, ...props }) => {
  const [isClicked, setIsClicked] = useState(false);
  const switchHandler = () => {
    setIsClicked((prevState) => !prevState);
  };

  return (
    <StyledBaseButton
      $isClicked={isClicked}
      onClick={()=> switchHandler()}
      {...props}
    >
      {children}
    </StyledBaseButton>
  );
};

export const MovieControlButton = ({ ref, ...props }) => {
  const [isClicked, setIsClicked] = useState(false);
  const switchHandler = () => {
    setIsClicked((prevState) => !prevState);
  };

  return (
    <StyledMovieControlButton
      $isClicked={isClicked}
      onClick={() => {
        isClicked ? ref.current.myPause() : ref.current.myPlay();
        switchHandler();
      }}
      {...props}
    >
      {isClicked ? "Stop" : "Play"}
    </StyledMovieControlButton>
  );
};