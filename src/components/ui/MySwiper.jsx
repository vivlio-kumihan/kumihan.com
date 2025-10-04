// Swiperのコンポーネント
import { Swiper, SwiperSlide } from "swiper/react";
// Swiperのモジュール
import { Navigation, Pagination, Autoplay } from "swiper/modules";
// SwiperのCSS
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

const MySwiper = () => {
  // スライドのデータ
  const slides = [
    { id: 1, title: 'SwiperSlide #1', text: "It's first slide.", color: '#3b82f6' },
    { id: 2, title: 'SwiperSlide #2', text: "It's second slide.", color: '#10b981' },
    { id: 3, title: 'SwiperSlide #3', text: "It's third slide.", color: '#8b5cf6' },
    { id: 4, title: 'SwiperSlide #4', text: "It's fourth slide.", color: '#f59e0b' },
    { id: 5, title: 'SwiperSlide #5', text: "It's last slide.", color: '#ec4899' },
  ];  

  return (
    <Swiper
      modules={[Navigation, Pagination, Autoplay]}
      spaceBetween={30}
      slidesPerView={1}
      navigation
      pagination={{ clickable: true }}
      autoplay={{ delay: 3000, disableOnInteraction: false }}
      loop={true}
      style={{ width: "100vw", height: "400px" }}
    >
      {slides.map((slide) => (
        <SwiperSlide key={slide.id}>
          <div
            style={{
              background: slide.color,
              height: "100%",
              display: "flex",
              flexDirection: "column",
              alignItems: "center",
              justifyContent: "center",
              color: "white",
            }}
          >
            <h2 style={{ fontSize: "32px", marginBottom: "10px" }}>
              {slide.title}
            </h2>
            <p style={{ fontSize: "18px" }}>{slide.text}</p>
          </div>
        </SwiperSlide>
      ))}
    </Swiper>
  );
};

export default MySwiper;
