import React, { useEffect, useState } from 'react';
import { motion } from 'motion/react';
import { Youtube, Play, ExternalLink, Loader2 } from 'lucide-react';

interface YouTubeVideo {
  id: string;
  title: string;
  thumbnail: string;
  publishedAt: string;
}

const HANDLE = '@aakhikh_the_house_of_blessings';
const API_KEY = import.meta.env.VITE_YOUTUBE_API_KEY;

const YouTubeSection: React.FC = () => {
  const [video, setVideo] = useState<YouTubeVideo | null>(null);
  const [loading, setLoading] = useState(true);
  const [isPlaying, setIsPlaying] = useState(false);

  useEffect(() => {
    const fetchLatestVideo = async () => {
      // Fallback data
      const fallbackVideo = {
        id: 'Xsh_z9_YFss', 
        title: 'Sunday Worship Experience - AAKHIKH',
        thumbnail: 'https://images.unsplash.com/photo-1510672981848-a1c4f1cb5ccf?auto=format&fit=crop&q=80',
        publishedAt: new Date().toISOString()
      };

      if (!API_KEY) {
        setVideo(fallbackVideo);
        setLoading(false);
        return;
      }

      try {
        // 1. Get channel ID from handle
        const channelResp = await fetch(
          `https://www.googleapis.com/youtube/v3/channels?key=${API_KEY}&forHandle=${HANDLE}&part=id`
        );
        const channelData = await channelResp.json();
        
        let channelId = '';
        if (channelData.items && channelData.items.length > 0) {
          channelId = channelData.items[0].id;
        }

        if (channelId) {
          // 2. Get latest video from channel
          const videoResp = await fetch(
            `https://www.googleapis.com/youtube/v3/search?key=${API_KEY}&channelId=${channelId}&part=snippet,id&order=date&maxResults=1&type=video`
          );
          const videoData = await videoResp.json();
          
          if (videoData.items && videoData.items.length > 0) {
            const item = videoData.items[0];
            setVideo({
              id: item.id.videoId,
              title: item.snippet.title,
              thumbnail: item.snippet.thumbnails.high.url,
              publishedAt: item.snippet.publishedAt
            });
            setLoading(false);
            return;
          }
        }
        
        setVideo(fallbackVideo);
      } catch (error) {
        console.error('Error fetching YouTube video:', error);
        setVideo(fallbackVideo);
      } finally {
        setLoading(false);
      }
    };

    fetchLatestVideo();
  }, []);

  if (loading) {
    return (
      <div className="h-[400px] flex items-center justify-center bg-white/5 rounded-[40px] border border-white/5">
        <Loader2 className="w-8 h-8 text-blue-600 animate-spin" />
      </div>
    );
  }

  if (!video) return null;

  return (
    <section className="py-24 px-6 bg-black overflow-hidden">
      <div className="max-w-7xl mx-auto">
        <div className="flex flex-col md:flex-row items-end justify-between mb-16 gap-8">
          <motion.div
            initial={{ opacity: 0, x: -20 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
          >
            <span className="text-red-600 font-bold uppercase tracking-[0.3em] text-xs mb-4 block italic flex items-center gap-2">
              <Youtube size={16} /> Live & Latest
            </span>
            <h2 className="text-5xl md:text-7xl font-black tracking-tighter italic leading-none uppercase text-white">
              WATCH US <br />IN ACTION.
            </h2>
          </motion.div>
          <motion.a 
            href="https://youtube.com/@aakhikh_the_house_of_blessings"
            target="_blank"
            rel="noopener noreferrer"
            initial={{ opacity: 0, x: 20 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            className="flex items-center gap-3 bg-red-600 text-white px-8 py-4 rounded-full font-black text-xs uppercase tracking-widest hover:bg-red-700 transition-all hover:scale-105 shadow-xl"
          >
            Subscribe Now <ExternalLink size={16} />
          </motion.a>
        </div>

        <div className="relative group">
          <motion.div 
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="aspect-video rounded-[40px] overflow-hidden bg-white/5 border border-white/10 shadow-3xl relative"
          >
            {!isPlaying ? (
              <>
                <img 
                  src={video.thumbnail} 
                  alt={video.title}
                  className="w-full h-full object-cover opacity-60 group-hover:opacity-80 transition-all duration-700 group-hover:scale-105"
                />
                <div className="absolute inset-0 flex flex-col items-center justify-center gap-6">
                  <button 
                    onClick={() => setIsPlaying(true)}
                    className="w-24 h-24 bg-red-600 text-white rounded-full flex items-center justify-center shadow-2xl hover:scale-110 active:scale-95 transition-all group/play relative"
                  >
                    <div className="absolute inset-0 bg-red-600 rounded-full animate-ping opacity-20" />
                    <Play size={40} className="fill-current ml-2" />
                  </button>
                  <div className="text-center px-6">
                    <h3 className="text-2xl md:text-4xl font-black italic uppercase tracking-tighter text-white mb-2 line-clamp-2">
                      {video.title}
                    </h3>
                    <p className="text-white/40 font-bold uppercase tracking-widest text-[10px]">
                      Latest Broadcast • {new Date(video.publishedAt).toLocaleDateString()}
                    </p>
                  </div>
                </div>
              </>
            ) : (
              <iframe
                src={`https://www.youtube.com/embed/${video.id}?autoplay=1`}
                title={video.title}
                className="w-full h-full"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowFullScreen
              />
            )}
          </motion.div>
          
          {/* Decorative Elements */}
          <div className="absolute -top-10 -right-10 w-40 h-40 bg-red-600/20 blur-[100px] pointer-events-none" />
          <div className="absolute -bottom-10 -left-10 w-60 h-60 bg-blue-600/10 blur-[120px] pointer-events-none" />
        </div>
      </div>
    </section>
  );
};

export default YouTubeSection;
