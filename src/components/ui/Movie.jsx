import { useRef, forwardRef, useImperativeHandle } from "react";

const Movie = forwardRef(({ path }, ref) => {
  const movieRef = useRef();
  useImperativeHandle(ref, () => (
    {
      myPause() { movieRef.current.pause() },
      myPlay() { movieRef.current.play() },
    }
  ));

  return (
    <video ref={movieRef}>
      <source src={path} />
    </video>
  );
});


export default Movie