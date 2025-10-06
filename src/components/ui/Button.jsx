import { useState, useRef } from "react";
import styled from "styled-components";
import { mq } from "./MediaQuerry";

const StyledBaseButton = styled.button`
  display: block;
  width: 150px;
  height: 70px;
  font-size: 12px;
  line-height: 1;
  font-weight: 700;
  color: ${({ $isClicked }) => ($isClicked ? "#777" : "#fff")};
  background-color: ${({ $isClicked }) => $isClicked ? "rgba(255, 165, 0, .4)" : "rgba(255, 165, 0, .9)"};
  border: unset;
  border-radius: 999px;
  transition: 0.3s;
  &:hover {
    color: rgba(0, 0, 0, .3);
    background-color: rgba(255, 165, 0, 1);
    box-shadow: 2px 2px 3px #777;
    transform: translateY(-3px);
  }
  ${mq.lg} {
    font-size: 15px;
  }
`;

const StyledMovieControlButton = styled(StyledBaseButton)`
  &:hover {
  }
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

export const MovieControlButton = ({ ref }) => {
  const [playing, setPlaying] = useState(false);
  const switchHandler = () => {
    setPlaying((prevState) => !prevState);
  };

  return (
    <StyledMovieControlButton
      $playing={playing}
      onClick={() => {
        playing ? ref.current.myPause() : ref.current.myPlay();
        switchHandler();
      }}
    >
      {playing ? "Stop" : "Play"}
    </StyledMovieControlButton>
  );
};