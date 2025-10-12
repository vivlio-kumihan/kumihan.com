import imageMap from "../../../public/images/gallery";

const photos = [
  {
    path: imageMap.img_120512_P1010192,
    caption: "撮影作品 #1 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "12/05/12",
  },
  {
    path: imageMap.img_120512_P1010482,
    caption: "撮影作品 #2 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "12/05/12",
  },
  {
    path: imageMap.img_210712_1130904,
    caption: "撮影作品 #3 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "21/07/12",
  },
  {
    path: imageMap.img_210806_1130956,
    caption: "撮影作品 #4 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "21/08/06",
  },
  {
    path: imageMap.img_211231_1133170,
    caption: "撮影作品 #5 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "21/12/31",
  },
  {
    path: imageMap.img_220110_1156190,
    caption: "撮影作品 #6 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/01/10",
  },
  {
    path: imageMap.img_220412_1001236,
    caption: "撮影作品 #7 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/04/12",
  },
  {
    path: imageMap.img_220504_1002538,
    caption: "撮影作品 #8 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/05/04",
  },
  {
    path: imageMap.img_220505_1002640,
    caption: "撮影作品 #9 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/05/05",
  },
  {
    path: imageMap.img_220508_1002741,
    caption: "撮影作品 #10 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/05/08",
  },
  {
    path: imageMap.img_220515_1023036,
    caption: "撮影作品 #11 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/05/15",
  },
  {
    path: imageMap.img_220523_1023281,
    caption: "撮影作品 #12 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/05/23",
  },
  {
    path: imageMap.img_220526_1023368,
    caption: "撮影作品 #13 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/05/26",
  },
  {
    path: imageMap.img_220528_1023405,
    caption: "撮影作品 #14 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/05/28",
  },
  {
    path: imageMap.img_220605_1023534,
    caption: "撮影作品 #15 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/06/05",
  },
  {
    path: imageMap.img_220708_PICT4507,
    caption: "撮影作品 #16 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/07/08",
  },
  {
    path: imageMap.img_220717_PICT4828,
    caption: "撮影作品 #17 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/07/17",
  },
  {
    path: imageMap.img_220814_A7D_PICT4891,
    caption: "撮影作品 #18 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/08/14",
  },
  {
    path: imageMap.img_220814_A7D_PICT4923,
    caption: "撮影作品 #19 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/08/14",
  },
  {
    path: imageMap.img_220814_A7D_PICT4926,
    caption: "撮影作品 #20 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/08/14",
  },
  {
    path: imageMap.img_220814_A7D_PICT4927,
    caption: "撮影作品 #21 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/08/14",
  },
  {
    path: imageMap.img_220814_A7D_PICT4928,
    caption: "撮影作品 #22 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/08/14",
  },
  {
    path: imageMap.img_220814_A7D_PICT4932,
    caption: "撮影作品 #23 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/08/14",
  },
  {
    path: imageMap.img_220814_A7D_PICT4935,
    caption: "撮影作品 #24 - 光と影の表現を意識した一枚です。",
    location: "大阪/枚方市",
    date: "22/08/14",
  },
];

// import した画像を使ってGalleryDataを生成
const GalleryData = photos.map((photo) => {
  const idName = photo.path.split("/").pop().replace(/\.[^.]+$/, "");
  return {
    id: idName,
    src: photo.path,
    caption: photo.caption,
    location: photo.location, // ← タイポ修正！
    date: photo.date,
  };
});
// const GalleryData = photos.map((photo) => ({
//   id: photo.path.split("/").pop().replace(/\.[^.]+$/, ""),
//   src: photo.path,
//   caption: photo.caption,
//   location: photo.location, // ← タイポ修正！
//   date: photo.date,
// }));

export default GalleryData;