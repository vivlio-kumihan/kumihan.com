import { useState, useEffect } from "react";
import styled from "styled-components";
import { mq } from "./MediaQuerry";

// ========================================
// Styled Components
// ========================================
const FeedSection = styled.section`
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

const PostGrid = styled.div`
  display: grid;
  grid-template-columns: 1fr;
  margin-bottom: 2rem;
  ${ mq.md } {
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
  ${ mq.lg } {
    grid-template-columns: repeat(3, 1fr);
  }
`;

const PostCard = styled.article`
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

const PostImage = styled.img`
  width: 100%;
  aspect-ratio: 1 / 1;
  object-fit: cover;
`;

const PostInfo = styled.div`
  padding: 1rem;
`;

const PostLikes = styled.p`
  font-size: 0.875rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
  color: #333;
`;

const PostCaption = styled.p`
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

const PostDate = styled.time`
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

// ========================================
// ダミーAPIフェッチ関数
// ========================================
const fetchInstagramPosts = async (page) => {
  // 実際のAPI呼び出しをシミュレート(1秒待機)
  await new Promise((resolve) => setTimeout(resolve, 1000));

  const startId = (page - 1) * 6 + 1;
  return Array.from({ length: 6 }, (_, i) => ({
    id: startId + i,
    imageUrl: `https://picsum.photos/400/400?random=${startId + i}`,
    caption: `撮影作品 #${startId + i} - 光と影の表現を意識した一枚です。`,
    likes: Math.floor(Math.random() * 500) + 50,
    date: `${Math.floor(Math.random() * 7) + 1}日前`,
    permalink: `https://instagram.com/p/example${startId + i}`,
  }));
};

// ========================================
// メインコンポーネント
// ========================================
function InstagramFeed() {
  const [posts, setPosts] = useState([]);
  const [page, setPage] = useState(1);
  const [loading, setLoading] = useState(false);
  const [hasMore, setHasMore] = useState(true);

  useEffect(() => {
    const loadPosts = async () => {
      try {
        setLoading(true);
        const newPosts = await fetchInstagramPosts(page);

        if (newPosts.length === 0) {
          setHasMore(false);
        } else {
          setPosts((prev) => [...prev, ...newPosts]);

          // ダミーデータなので3ページ(18投稿)で終了
          if (page >= 3) {
            setHasMore(false);
          }
        }
      } catch (error) {
        console.error("Instagram投稿の取得に失敗しました:", error);
      } finally {
        setLoading(false);
      }
    };

    loadPosts();
  }, [page]);

  const handleLoadMore = () => {
    setPage((prev) => prev + 1);
  };

  const handlePostClick = (permalink) => {
    // Instagramの投稿ページを新しいタブで開く
    window.open(permalink, "_blank", "noopener,noreferrer");
  };

  return (
    <FeedSection>
      <SectionTitle>📷 Instagram</SectionTitle>
      <SectionSubtitle>最新の撮影作品をチェック</SectionSubtitle>

      <PostGrid>
        {posts.map((post) => (
          <PostCard
            key={post.id}
            onClick={() => handlePostClick(post.permalink)}
          >
            <PostImage src={post.imageUrl} alt={post.caption} />
            <PostInfo>
              <PostLikes>❤️ {post.likes}</PostLikes>
              <PostCaption>{post.caption}</PostCaption>
              <PostDate>{post.date}</PostDate>
            </PostInfo>
          </PostCard>
        ))}
      </PostGrid>

      {loading && (
        <LoadingContainer>
          <Spinner />
          <LoadingText>読み込み中...</LoadingText>
        </LoadingContainer>
      )}

      {!loading && hasMore && (
        <LoadMoreButton onClick={handleLoadMore}>もっと見る ▼</LoadMoreButton>
      )}

      {!loading && !hasMore && posts.length > 0 && (
        <EndMessage>すべての投稿を表示しました</EndMessage>
      )}
    </FeedSection>
  );
}

export default InstagramFeed;