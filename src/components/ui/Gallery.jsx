import { useState, useEffect } from "react";
import styled from "styled-components";
import { mq } from "./MediaQuerry";
import GalleryData from "./GalleryData";

// ========================================
// Styled Components
// ========================================
const GallerySection = styled.section`
  padding: 4rem 2rem;
  max-width: 1200px;
  margin: 0 auto;
`;

const SectionTitle = styled.h2`
  font-size: 2rem;
  margin-bottom: 0.5rem;
  text-align: center;
`;

const SectionSubtitle = styled.p`
  color: #666;
  text-align: center;
  margin-bottom: 3rem;
`;

const PhotoGrid = styled.div`
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
  margin-bottom: 2rem;
  ${mq.md} {
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
  ${mq.lg} {
    grid-template-columns: repeat(3, 1fr);
  }
`;

const PhotoCard = styled.article`
  background-color: white;
  border: 1px solid #ddd;
  border-radius: 8px;
  overflow: hidden;
  transition: transform 0.2s, box-shadow 0.2s;
  cursor: pointer;
  &:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }
`;

const PhotoImage = styled.img`
  width: 100%;
  aspect-ratio: 1 / 1;
  object-fit: cover;
`;

const PhotoInfo = styled.div`
  padding: 1rem;
`;

const PhotoLocation = styled.p`
  font-size: 0.875rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
  color: #333;
`;

const PhotoCaption = styled.p`
  font-size: 0.875rem;
  color: #555;
  margin-bottom: 0.5rem;
  line-height: 1.4;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
`;

const PhotoDate = styled.time`
  font-size: 0.75rem;
  color: #999;
`;

const LoadingContainer = styled.div`
  text-align: center;
  padding: 2rem;
`;

const Spinner = styled.div`
  display: inline-block;
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3182ce;
  border-radius: 50%;
  animation: spin 1s linear infinite;

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
`;

const LoadingText = styled.p`
  margin-top: 1rem;
  color: #666;
`;

const LoadMoreButton = styled.button`
  display: block;
  margin: 0 auto;
  padding: 1rem 3rem;
  font-size: 1rem;
  font-weight: bold;
  color: white;
  background-color: #3182ce;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.2s;

  &:hover {
    background-color: #2c5282;
  }

  &:disabled {
    background-color: #a0aec0;
    cursor: not-allowed;
  }
`;

const EndMessage = styled.p`
  text-align: center;
  color: #666;
  font-size: 0.875rem;
  padding: 2rem;
`;

const Modal = styled.div`
  display: flex;
  align-items: center;
  justify-content: center;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.9);
  transition: all 0.3s;
  z-index: 50;
  cursor: pointer;
  animation: appear .5s;

  @keyframes appear {
    0% {
      opacity: 0;
    }
    100% {
      opacity: 1;
    }
  }
`;

const ModalImage = styled.img`
  max-width: 90%;
  max-height: 90%;
  object-fit: contain;
`;

const CloseButton = styled.button`
  position: absolute;
  top: 20px;
  right: 20px;
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: white;
  font-size: 2rem;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  cursor: pointer;
  transition: background 0.2s;

  &:hover {
    background: rgba(255, 255, 255, 0.3);
  }
`;

// ========================================
// 画像を段階的に読み込む関数
// ========================================
const loadPhotos = async (page, itemsPerPage = 6) => {
  // 読み込みをシミュレート
  await new Promise((resolve) => setTimeout(resolve, 500));

  const startIndex = (page - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;

  return GalleryData.slice(startIndex, endIndex);
};

// ========================================
// メインコンポーネント
// ========================================
function Gallery() {
  const [photos, setPhotos] = useState([]);
  const [page, setPage] = useState(1);
  const [loading, setLoading] = useState(false);
  const [hasMore, setHasMore] = useState(true);
  const [modalImage, setModalImage] = useState(null);

  useEffect(() => {
    const loadGallery = async () => {
      try {
        setLoading(true);
        const newPhotos = await loadPhotos(page);

        // 新しい写真がない場合
        if (newPhotos.length === 0) {
          setHasMore(false);
          return;
        }

        // 写真を追加
        setPhotos((prev) => {
          // 重複チェック：既存のIDを確認
          const existingIds = new Set(prev.map((p) => p.id));
          const uniqueNewPhotos = newPhotos.filter(
            (p) => !existingIds.has(p.id)
          );

          const updated = [...prev, ...uniqueNewPhotos];
          console.log(
            `Total photos: ${updated.length} / ${GalleryData.length}`
          );

          // すべての写真を表示したかチェック
          if (updated.length >= GalleryData.length) {
            setHasMore(false);
          }

          return updated;
        });
      } catch (error) {
        console.error("写真の取得に失敗しました:", error);
      } finally {
        setLoading(false);
      }
    };

    loadGallery();
  }, [page]);

  const handleLoadMore = () => {
    setPage((prev) => prev + 1);
  };

  const handlePhotoClick = (src) => {
    setModalImage(src);
  };

  const closeLightbox = () => {
    setModalImage(null);
  };

  return (
    <>
      <GallerySection>
        <SectionTitle>📷 Photo Gallery</SectionTitle>
        <SectionSubtitle>最新の撮影作品をチェック</SectionSubtitle>

        <PhotoGrid>
          {photos.map((photo) => (
            <PhotoCard
              key={photo.id}
              onClick={() => handlePhotoClick(photo.src)}
            >
              <PhotoImage src={photo.src} alt={photo.caption} />
              <PhotoInfo>
                <PhotoLocation>📍 {photo.location}</PhotoLocation>
                <PhotoCaption>{photo.caption}</PhotoCaption>
                <PhotoDate>{photo.date}</PhotoDate>
              </PhotoInfo>
            </PhotoCard>
          ))}
        </PhotoGrid>

        {loading && (
          <LoadingContainer>
            <Spinner />
            <LoadingText>読み込み中...</LoadingText>
          </LoadingContainer>
        )}

        {!loading && hasMore && (
          <LoadMoreButton onClick={handleLoadMore}>もっと見る ▼</LoadMoreButton>
        )}

        {!loading && !hasMore && photos.length > 0 && (
          <EndMessage>すべての作品を表示しました</EndMessage>
        )}
      </GallerySection>

      {modalImage && (
        <Modal onClick={closeLightbox}>
          <CloseButton onClick={closeLightbox}>×</CloseButton>
          <ModalImage src={modalImage} alt="拡大表示" />
        </Modal>
      )}
    </>
  );
}

export default Gallery;