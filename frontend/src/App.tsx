import { motion, AnimatePresence } from 'motion/react';
import {
  Heart,
  MapPin,
  Mail,
  Phone,
  Youtube,
  Facebook,
  Instagram,
  Play,
  ArrowRight,
  BookOpen,
  Users,
  Calendar,
  ChevronDown,
  Star,
  ShoppingBag,
  Send,
  Quote,
  LogIn,
  LogOut,
  Settings,
  X,
  Smartphone,
  CreditCard,
  CheckCircle2
} from 'lucide-react';
import { useState, useEffect, FormEvent } from 'react';
import { BrowserRouter as Router, Routes, Route, Link, useLocation } from 'react-router-dom';
import { auth, signInWithGoogle, logout } from './lib/firebase';
import { onAuthStateChanged, User } from 'firebase/auth';
import { CartProvider } from './context/CartContext';
import Store from './components/store/Store';
import Admin from './components/store/Admin';
import YouTubeSection from './components/YouTubeSection';

// --- Components ---

const Navbar = () => {
  const [isScrolled, setIsScrolled] = useState(false);
  const [user, setUser] = useState<User | null>(null);
  const location = useLocation();
  const isStore = location.pathname.startsWith('/store') || location.pathname.startsWith('/admin');

  useEffect(() => {
    const handleScroll = () => setIsScrolled(window.scrollY > 50);
    window.addEventListener('scroll', handleScroll);
    const unsubscribe = onAuthStateChanged(auth, (u) => setUser(u));
    return () => {
      window.removeEventListener('scroll', handleScroll);
      unsubscribe();
    };
  }, []);

  const ADMIN_EMAIL = 'chetri.prem999@gmail.com';
  const isAdmin = user?.email?.toLowerCase() === ADMIN_EMAIL.toLowerCase();

  return (
    <nav
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-500 flex justify-center ${isScrolled ? 'py-2 sm:py-3' : 'py-6'
        }`}
    >
      <div
        className={`w-full max-w-7xl mx-4 sm:mx-6 flex items-center justify-between transition-all duration-500 ${(isScrolled || isStore)
          ? 'bg-black/80 backdrop-blur-xl border border-white/10 px-6 py-2 rounded-full shadow-2xl'
          : 'bg-transparent px-2'
          }`}
      >
        <Link to="/" className="flex items-center gap-3">
          <div className="w-9 h-9 rounded-full bg-white flex items-center justify-center font-black text-black text-lg shadow-xl">
            A
          </div>
          <span className="font-black tracking-[0.2em] text-lg text-white uppercase italic">
            AAKHIKH
          </span>
        </Link>

        <div className="hidden lg:flex flex-1 justify-center items-center gap-8 text-[10px] font-black uppercase tracking-[0.2em] text-white/70">
          <Link to="/" className="hover:text-white transition-colors">Home</Link>
          <a href="/#story" className="hover:text-white transition-colors">Our History</a>
          <a href="/#events" className="hover:text-white transition-colors">Events</a>
          <Link to="/store" className="hover:text-white transition-colors text-blue-400">Merch Store</Link>
          <a href="/#giving" className="hover:text-white transition-colors">Giving</a>
          {isAdmin && (
            <Link to="/admin" className="text-yellow-500 hover:text-yellow-400 flex items-center gap-1">
              <Settings size={12} /> Admin
            </Link>
          )}
        </div>

        <div className="flex items-center gap-4">
          {user ? (
            <div className="flex items-center gap-3 md:gap-4">
              {isAdmin && (
                <Link
                  to="/admin"
                  className="p-2 bg-white/5 rounded-full text-yellow-500 hover:bg-white/10 transition-all"
                  title="Admin Panel"
                >
                  <Settings size={18} />
                </Link>
              )}
              <img src={user.photoURL || ''} alt="" className="w-8 h-8 rounded-full border border-white/10" />
              <button
                onClick={logout}
                className="text-white/50 hover:text-white transition-colors"
                title="Logout"
              >
                <LogOut size={18} />
              </button>
            </div>
          ) : (
            <button
              onClick={signInWithGoogle}
              className="px-6 py-2.5 bg-white text-black rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 transition-all active:scale-95 shadow-xl flex items-center gap-2"
            >
              <LogIn size={14} /> Join Us
            </button>
          )}
        </div>
      </div>
    </nav>
  );
};

const Hero = () => {
  return (
    <section id="home" className="relative h-screen w-full flex items-center justify-center overflow-hidden">
      <div className="absolute inset-0 z-0">
        <video
          autoPlay
          muted
          loop
          playsInline
          className="w-full h-full object-cover"
        >
          <source src="/video/hero-video-akhikh.mp4" type="video/mp4" />
        </video>
        <div className="absolute inset-0 bg-black/60" />
      </div>

      <motion.div
        initial={{ opacity: 0, y: 30 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 1, ease: "easeOut" }}
        className="relative z-10 text-center px-4 max-w-6xl"
      >
        <span className="text-blue-500 font-black uppercase tracking-[0.4em] text-[10px] mb-8 block mb-4">
          WELCOME TO AAKHIKH: THE HOUSE OF BLESSINGS
        </span>
        <h1 className="text-white text-5xl md:text-[110px] font-black leading-[0.8] tracking-tighter mb-12 italic uppercase">
          SPREADING HOPE. <br />
          BUILDING FAITH.
        </h1>
        <div className="flex flex-col sm:flex-row items-center justify-center gap-6 mt-4">
          <button className="bg-blue-600 text-white px-10 py-4 rounded-full font-black text-[11px] uppercase tracking-widest hover:bg-blue-700 transition-all transform hover:scale-105 shadow-[0_0_30px_rgba(37,99,235,0.4)] flex items-center gap-2">
            Plan A Visit <ArrowRight size={14} className="stroke-[3px]" />
          </button>
          <button className="bg-black/40 hover:bg-black/60 backdrop-blur-md text-white border border-white/20 px-10 py-4 rounded-full font-black text-[11px] uppercase tracking-widest transition-all transform hover:scale-105 flex items-center gap-2">
            Watch Online <Play size={14} fill="white" />
          </button>
        </div>
      </motion.div>
    </section>
  );
};

const ScrollingTicker = () => {
  return (
    <div className="bg-black py-16 overflow-hidden whitespace-nowrap border-y border-white/5 relative z-20">
      <motion.div
        animate={{ x: [0, -1500] }}
        transition={{ repeat: Infinity, duration: 40, ease: "linear" }}
        className="flex items-center gap-24 text-6xl md:text-8xl font-black text-white/5 uppercase tracking-tighter select-none italic"
      >
        <span className="text-white/10 group-hover:text-white transition-colors duration-500">Welcome Home</span>
        <span className="text-white border-[3px] border-white/10 rounded-full px-12 py-3 bg-white/5">House of Blessings</span>
        <span className="text-blue-500/10">Divine Purpose</span>
        <span className="text-white border-[3px] border-white/10 rounded-full px-12 py-3 bg-white/5">Love Grace Truth</span>
        <span className="text-white/10 italic">Welcome Home</span>
        <span className="text-blue-500/10">House of Blessings</span>
        <span className="text-white border-[3px] border-white/10 rounded-full px-12 py-3 bg-white/5">Spreading Hope</span>
      </motion.div>
    </div>
  );
};

const OurStorySection = () => {
  return (
    <section id="story" className="py-32 bg-black text-white px-6 overflow-hidden relative">
      <div className="max-w-7xl mx-auto">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
          <motion.div
            initial={{ opacity: 0, x: -50 }}
            whileInView={{ opacity: 1, x: 0 }}
            transition={{ duration: 1.2 }}
            viewport={{ once: true }}
            className="relative"
          >
            <div className="absolute -top-20 -left-20 w-60 h-60 bg-blue-600/10 rounded-full blur-[120px]" />
            <img
              src="https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&q=80&w=1200"
              alt="Community Life"
              className="rounded-[60px] shadow-5xl relative z-10 border border-white/5 hover:scale-[1.02] transition-transform duration-1000"
            />
          </motion.div>
          <motion.div
            initial={{ opacity: 0, x: 50 }}
            whileInView={{ opacity: 1, x: 0 }}
            transition={{ duration: 1, delay: 0.2 }}
            viewport={{ once: true }}
          >
            <span className="text-blue-500 font-black uppercase tracking-[0.4em] text-[10px] mb-8 block italic">WHO WE ARE</span>
            <h2 className="text-6xl md:text-[80px] font-black tracking-tighter mb-10 leading-[0.8] italic uppercase">
              Our Story.
            </h2>
            <p className="text-gray-400 text-xl leading-relaxed mb-8 italic font-medium">
              AAKHIKH began with a simple vision: to create a home where every soul is blessed and every heart finds hope.
            </p>
            <p className="text-gray-500 leading-relaxed mb-12 text-sm font-medium">
              In the heart of North-Eastern India, we've grown from a small prayer circle into a vibrant, non-denominational community. Our name, meaning "Blessing," reflects our core mission—to be a conduit of God's love to our region.
            </p>
            <button className="bg-white text-black px-12 py-5 rounded-full font-black text-[11px] uppercase tracking-widest hover:bg-gray-200 transition-all flex items-center gap-4 shadow-3xl">
              Discover Our History <ArrowRight size={18} className="stroke-[3px]" />
            </button>
          </motion.div>
        </div>
      </div>
    </section>
  );
};

const MinistryGrid = () => {
  const ministries = [
    {
      title: "Our History",
      desc: "From small home fellowship to a regional beacon.",
      img: "https://images.unsplash.com/photo-1544427928-c49cdfebf494?auto=format&fit=crop&q=80&w=800",
      link: "#history"
    },
    {
      title: "Events",
      desc: "Join us for our upcoming 1-Year Anniversary.",
      img: "https://images.unsplash.com/photo-1472653431158-6364773b2a56?auto=format&fit=crop&q=80&w=800",
      link: "#events"
    },
    {
      title: "Next Steps",
      desc: "Grow your spiritual journey with our community.",
      img: "https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&q=80&w=800",
      link: "#leadership"
    }
  ];

  return (
    <section className="py-24 px-6 bg-white relative overflow-hidden">
      <div className="absolute top-10 left-0 w-full flex justify-between px-6 opacity-[0.03] pointer-events-none select-none">
        <span className="text-[120px] font-black uppercase text-black leading-none">HOUSE OF BLESSINGS</span>
        <span className="text-[120px] font-black uppercase text-blue-600 leading-none">WELCOME</span>
      </div>

      <div className="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
        {ministries.map((m, i) => (
          <motion.div
            key={i}
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ delay: i * 0.1, duration: 0.8 }}
            viewport={{ once: true }}
            whileHover={{ y: -15 }}
            className="group relative h-[550px] overflow-hidden rounded-[40px] cursor-pointer shadow-3xl"
          >
            <img src={m.img} alt={m.title} className="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
            <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent" />
            <div className="absolute bottom-0 left-0 p-10">
              <h3 className="text-4xl font-black text-white mb-3 leading-none uppercase italic tracking-tighter">{m.title}</h3>
              <p className="text-white/60 text-sm mb-6 max-w-[200px] font-medium">{m.desc}</p>
              <div className="flex items-center gap-2 text-white font-black text-[10px] uppercase tracking-widest border-b-2 border-white pb-1 w-fit group-hover:gap-4 transition-all">
                Learn More <ArrowRight size={14} className="stroke-[3px]" />
              </div>
            </div>
          </motion.div>
        ))}
      </div>
    </section>
  );
};

const LeadershipSection = () => {
  return (
    <section id="leadership" className="py-24 bg-white px-6 overflow-hidden">
      <div className="max-w-6xl mx-auto">
        <div className="text-center mb-20">
          <h2 className="text-4xl md:text-5xl font-black italic text-black mb-4">Leading People to God</h2>
          <p className="text-gray-400 text-sm md:text-base max-w-2xl mx-auto font-medium leading-relaxed">
            Meet the leaders dedicated to the mission of AAKHIKH, serving the community in North-Eastern India with grace and truth.
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8 }}
            viewport={{ once: true }}
            className="relative rounded-[32px] overflow-hidden shadow-2xl"
          >
            <img
              src="https://images.unsplash.com/photo-1529699211952-734e80c4d42b?auto=format&fit=crop&q=80&w=1200"
              alt="Chess Pieces"
              className="w-full object-cover aspect-[4/5]"
            />
          </motion.div>

          <motion.div
            initial={{ opacity: 0, x: 20 }}
            whileInView={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.8, delay: 0.2 }}
            viewport={{ once: true }}
            className="lg:pl-6"
          >
            <span className="text-blue-600 font-bold uppercase tracking-[0.1em] text-[9px] mb-6 block">OUR SENIOR LEADERSHIP</span>
            <h3 className="text-3xl md:text-5xl font-bold mb-8 text-black leading-[1.1] tracking-tight">
              Rev. Sonia Bhattacharya <br />& Dr. Nabarun Dhar
            </h3>
            <p className="text-gray-400 text-sm leading-relaxed mb-10 font-medium max-w-lg">
              Rev. Sonia Bhattacharya serves as the Senior Pastor, while Dr. Nabarun Dhar is the Co-founding Pastor. Together with our team of Elders and Deacons, they lead AAKHIKH with a vision to build a non-denominational, Bible-based community.
            </p>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-12">
              <div className="border-l-2 border-blue-600 pl-5">
                <p className="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1.5 opacity-60">ELDERS</p>
                <p className="text-sm font-bold text-black leading-relaxed">
                  Monica, Wanshai, Rashmi, Sanju
                </p>
              </div>
              <div className="border-l-2 border-gray-200 pl-5">
                <p className="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1.5 opacity-60">DEACONS</p>
                <p className="text-sm font-bold text-black leading-relaxed">
                  Arpana, Nayanjyoti
                </p>
              </div>
            </div>

            <button className="bg-black text-white px-10 py-3 rounded-full font-bold text-xs flex items-center gap-3 hover:bg-gray-800 transition-all shadow-xl">
              Read Statement of Faith <ArrowRight size={14} className="stroke-[3px]" />
            </button>
          </motion.div>
        </div>
      </div>
    </section>
  );
};

const BeliefsSection = () => {
  const beliefs = [
    { title: "The Trinity", text: "God the Father, Son, and Holy Spirit." },
    { title: "The Bible", text: "The inspired and infallible Word of God." },
    { title: "Salvation", text: "Redemptive power through Jesus Christ." },
    { title: "Holy Spirit", text: "Current works and gifts of the Spirit." },
    { title: "The Church", text: "Unity as one body in Christ." },
    { title: "The Future", text: "Visible return of Jesus Christ." }
  ];

  return (
    <section id="beliefs" className="py-24 bg-black text-white px-6">
      <div className="max-w-7xl mx-auto">
        <div className="mb-20">
          <h2 className="text-6xl font-black tracking-tighter mb-4 italic uppercase">What We Believe</h2>
          <p className="text-gray-500 max-w-xl font-medium">Our core values and scriptural foundation that guide every aspect of our ministry.</p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-x-12 gap-y-16">
          {beliefs.map((b, i) => (
            <motion.div
              key={i}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: i * 0.1 }}
              viewport={{ once: true }}
            >
              <div className="w-12 h-[3px] bg-blue-600 mb-6" />
              <h4 className="text-xl font-black mb-3 uppercase tracking-tight italic">{b.title}</h4>
              <p className="text-gray-500 text-sm leading-relaxed font-medium">{b.text}</p>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
};

const UpcomingEvents = () => {
  const events = [
    {
      title: "1-Year Anniversary",
      date: "March 29",
      desc: "Celebrating a year of God's grace and community growth.",
      img: "https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&q=80&w=800",
      type: "CELEBRATION"
    },
    {
      title: "Sunday Service",
      date: "Every Sunday, 10:00 AM",
      desc: "Join us for a time of worship, word, and fellowship.",
      img: "https://images.unsplash.com/photo-1544427928-c49cdfebf494?auto=format&fit=crop&q=80&w=800",
      type: "SUNDAY SERVICE"
    },
    {
      title: "Daily Bread Prayer",
      date: "Mon-Sat, 7:00 AM",
      desc: "Daily morning prayer and devotional series.",
      img: "https://images.unsplash.com/photo-1490730141103-6cac27aaab94?auto=format&fit=crop&q=80&w=800",
      type: "PRAYER"
    }
  ];

  return (
    <section id="events" className="py-24 px-6 bg-white overflow-hidden">
      <div className="max-w-7xl mx-auto">
        <div className="text-center mb-16">
          <span className="text-blue-600 font-black uppercase tracking-[0.4em] text-[9px] mb-4 block italic">LATEST HAPPENINGS</span>
          <h2 className="text-6xl font-black tracking-tighter mb-4 italic text-black uppercase">Upcoming Events</h2>
          <p className="text-gray-400 max-w-xl mx-auto font-medium">Stay engaged and grow in community by joining our upcoming events.</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-10">
          {events.map((e, i) => (
            <motion.div
              key={i}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ delay: i * 0.1, duration: 0.8 }}
              viewport={{ once: true }}
              className="bg-white rounded-[40px] overflow-hidden group transition-all shadow-4xl hover:shadow-5xl"
            >
              <div className="h-56 relative overflow-hidden">
                <img src={e.img} alt={e.title} className="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
                <div className="absolute top-6 left-6 bg-white/90 backdrop-blur-md px-4 py-1 rounded-full text-[8px] text-black font-black uppercase tracking-widest border border-gray-100 shadow-sm">
                  {e.type}
                </div>
              </div>
              <div className="p-10">
                <div className="flex items-center gap-2 text-blue-600 text-[10px] font-black mb-3 uppercase tracking-widest">
                  <Calendar size={14} className="stroke-[3px]" /> {e.date}
                </div>
                <h4 className="text-2xl font-black mb-6 text-black uppercase tracking-tighter italic leading-none">{e.title}</h4>
                <p className="text-gray-400 text-sm mb-8 leading-relaxed font-medium">{e.desc}</p>
                <div className="flex items-center gap-2 text-black font-black text-[10px] uppercase tracking-widest border-b-[3px] border-yellow-400 pb-1 w-fit group-hover:gap-4 transition-all">
                  Event Details <ArrowRight size={14} className="stroke-[3px]" />
                </div>
              </div>
            </motion.div>
          ))}
        </div>
        <div className="mt-20 text-center">
          <button className="bg-yellow-400 text-black px-12 py-5 rounded-full font-black text-xs uppercase tracking-widest hover:bg-yellow-500 transition-all hover:scale-110 shadow-2xl">
            View More Events
          </button>
        </div>
      </div>
    </section>
  );
};

const SermonExperience = () => {
  const sermons = [
    { title: "Living in Grace", speaker: "Rev. Sonia Bhattacharya", img: "https://images.unsplash.com/photo-1499209974431-9dac3adaf474?auto=format&fit=crop&q=80&w=800" },
    { title: "The Power of Faith", speaker: "Dr. Nabarun Dhar", img: "https://images.unsplash.com/photo-1544427928-c49cdfebf494?auto=format&fit=crop&q=80&w=800" },
    { title: "North-East Outreach", speaker: "Ministry Highlight", img: "https://images.unsplash.com/photo-1472653431158-6364773b2a56?auto=format&fit=crop&q=80&w=800" }
  ];

  return (
    <section id="sermons" className="py-32 bg-[#1133aa] text-white px-6 overflow-hidden">
      <div className="max-w-7xl mx-auto">
        <div className="flex flex-col lg:flex-row lg:items-end justify-between mb-20 gap-10">
          <div className="max-w-xl">
            <span className="text-white/40 font-black uppercase tracking-[0.4em] text-[9px] mb-6 block italic">WATCH & LISTEN</span>
            <h2 className="text-7xl md:text-[90px] font-black tracking-tighter leading-[0.8] italic uppercase">Worship <br />Experiences.</h2>
          </div>
          <button className="flex items-center gap-3 bg-white text-[#1133aa] px-10 py-4 rounded-full font-black text-[11px] uppercase tracking-widest hover:bg-gray-100 transition-all active:scale-95 shadow-2xl shrink-0">
            Latest Sermons <ArrowRight size={16} className="stroke-[3px]" />
          </button>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-10">
          {sermons.map((s, i) => (
            <motion.div
              key={i}
              initial={{ opacity: 0, y: 40 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ delay: i * 0.1, duration: 0.8 }}
              viewport={{ once: true }}
              whileHover={{ y: -20 }}
              className="relative aspect-[3/4.5] rounded-[50px] overflow-hidden group cursor-pointer shadow-4xl"
            >
              <img src={s.img} alt={s.title} className="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
              <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/10 to-transparent" />
              <div className="absolute bottom-0 left-0 p-10">
                <h4 className="text-3xl font-black mb-2 tracking-tighter uppercase italic leading-none group-hover:text-blue-400 transition-colors">{s.title}</h4>
                <p className="text-white/50 text-[10px] font-black uppercase tracking-widest">{s.speaker}</p>
              </div>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
};

const DailyBread = () => {
  return (
    <section id="resources" className="py-24 px-6 bg-white overflow-hidden">
      <div className="max-w-7xl mx-auto">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="flex flex-col md:flex-row items-end justify-between mb-16 gap-6"
        >
          <div className="max-w-xl">
            <div className="inline-block mb-3">
              <span className="text-blue-600 font-bold uppercase tracking-[0.2em] text-[11px] block">RESOURCES</span>
              <div className="h-[2px] w-full bg-blue-600 mt-1" />
            </div>
            <h2 className="text-6xl font-black tracking-tight leading-none italic text-black">Daily Bread</h2>
          </div>
          <p className="text-gray-400 max-w-sm md:text-right text-sm font-medium leading-relaxed">
            Nourish your soul with daily scripture, reflections, and prayers curated for your spiritual growth.
          </p>
        </motion.div>

        <div className="bg-[#fcfdfe] rounded-[60px] p-8 lg:p-20 flex flex-col lg:flex-row items-center gap-12 lg:gap-24 relative border border-gray-100/50">
          <motion.div
            initial={{ opacity: 0, scale: 0.95, rotate: 0 }}
            whileInView={{ opacity: 1, scale: 1, rotate: -2 }}
            transition={{ duration: 0.8, type: "spring" }}
            viewport={{ once: true, margin: "-100px" }}
            className="w-full lg:w-[45%] relative z-10"
          >
            <div className="bg-white p-10 md:p-14 rounded-[32px] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.08)] relative overflow-hidden group">
              <div className="relative z-10">
                <span className="text-gray-400 text-[10px] font-black uppercase block mb-8 tracking-[0.2em]">TODAY'S REFLECTION</span>
                <p className="text-3xl md:text-4xl font-serif italic text-black leading-[1.3] mb-10 font-normal">
                  "For I know the plans I have for you," declares the Lord, "plans to prosper you and not to harm you, plans to give you hope and a future."
                </p>
                <div className="flex items-center gap-4">
                  <div className="h-[2px] w-8 bg-blue-600/30" />
                  <span className="text-blue-600 font-bold text-base italic">— Jeremiah 29:11</span>
                </div>
              </div>
            </div>
          </motion.div>

          <motion.div
            initial={{ opacity: 0, x: 30 }}
            whileInView={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.8, delay: 0.2 }}
            viewport={{ once: true }}
            className="w-full lg:w-[45%] flex flex-col justify-center space-y-10"
          >
            <div className="space-y-8">
              <div className="flex items-center gap-5 group">
                <div className="bg-blue-50 p-4 rounded-2xl text-blue-600 transition-colors duration-300 shrink-0">
                  <BookOpen size={24} />
                </div>
                <div>
                  <h4 className="font-black text-xl text-black tracking-tight">Scripture for Today</h4>
                  <p className="text-gray-400 text-sm font-medium">Experience the living Word of God every morning.</p>
                </div>
              </div>
              <div className="flex items-center gap-5 group">
                <div className="bg-purple-50 p-4 rounded-2xl text-purple-600 transition-colors duration-300 shrink-0">
                  <Users size={24} />
                </div>
                <div>
                  <h4 className="font-black text-xl text-black tracking-tight">Community Reflection</h4>
                  <p className="text-gray-400 text-sm font-medium">Join others in meditating on God's grace.</p>
                </div>
              </div>
            </div>
            <button className="bg-[#1a56ff] text-white px-10 py-4 rounded-2xl font-black text-sm transition-all shadow-lg hover:bg-blue-700 active:scale-95 self-start">
              Subscribe to Newsletter
            </button>
          </motion.div>
        </div>
      </div>
    </section>
  );
};

const TestimoniesSection = () => {
  const testimonials = [
    {
      name: "Sandeep Das",
      role: "Community Member",
      text: "Coming to AAKHIKH felt like finally finding a home. The message of hope and the amazing community changed my life.",
      avatar: "https://i.pravatar.cc/150?u=sandeep"
    },
    {
      name: "Priya Sharma",
      role: "Worship Team",
      text: "The spiritual growth I've experienced here is beyond words. It's a place where you can truly encounter God's presence.",
      avatar: "https://i.pravatar.cc/150?u=priya"
    },
    {
      name: "Rahul Gogoi",
      role: "Youth Leader",
      text: "AAKHIKH isn't just a church; it's a family that empowers you to live out your faith in every aspect of life.",
      avatar: "https://i.pravatar.cc/150?u=rahul"
    }
  ];

  return (
    <section id="testimonies" className="py-24 bg-black text-white px-6 overflow-hidden">
      <div className="max-w-7xl mx-auto text-center mb-16">
        <span className="text-blue-500 font-bold uppercase tracking-[0.3em] text-xs mb-4 block italic">Testimonies</span>
        <h2 className="text-5xl font-black tracking-tighter italic uppercase">LIVES CHANGED.</h2>
      </div>
      <div className="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
        {testimonials.map((t, i) => (
          <motion.div
            key={i}
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ delay: i * 0.1 }}
            viewport={{ once: true }}
            className="bg-[#111] p-10 rounded-3xl border border-white/5 relative group hover:border-blue-500/30 transition-all shadow-2xl"
          >
            <Quote className="absolute top-8 right-8 text-white/5 group-hover:text-blue-500/20 transition-colors" size={64} />
            <div className="flex items-center gap-2 text-blue-500 mb-6">
              {[...Array(5)].map((_, j) => <Star key={j} size={14} fill="currentColor" />)}
            </div>
            <p className="text-gray-400 leading-relaxed mb-8 relative z-10 italic">"{t.text}"</p>
            <div className="flex items-center gap-4">
              <img src={t.avatar} alt={t.name} className="w-12 h-12 rounded-full grayscale opacity-70 group-hover:grayscale-0 group-hover:opacity-100 transition-all shadow-md" />
              <div>
                <h4 className="font-bold text-sm tracking-tight text-white">{t.name}</h4>
                <p className="text-blue-500 text-[10px] font-bold uppercase tracking-widest">{t.role}</p>
              </div>
            </div>
          </motion.div>
        ))}
      </div>
    </section>
  );
};

const StorePreview = () => {
  const items = [
    { title: "Blessing Hoodie", price: "₹1,499", img: "https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&q=80&w=600" },
    { title: "Faith Canvas Tote", price: "₹499", img: "https://images.unsplash.com/photo-1544816153-097305944321?auto=format&fit=crop&q=80&w=600" },
    { title: "Hope Journal", price: "₹699", img: "https://images.unsplash.com/photo-1544816153-3a002934ecbc?auto=format&fit=crop&q=80&w=600" },
    { title: "Signature Cap", price: "₹899", img: "https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&q=80&w=600" }
  ];

  return (
    <section id="shop" className="py-24 bg-[#050505] text-white px-6">
      <div className="max-w-7xl mx-auto flex flex-col md:flex-row items-end justify-between mb-16 gap-8">
        <div>
          <span className="text-blue-500 font-bold uppercase tracking-[0.3em] text-xs mb-4 block italic">Merchandise</span>
          <h2 className="text-5xl md:text-6xl font-black tracking-tighter italic leading-none uppercase text-white">THE BLESSING <br />COLLECTION.</h2>
        </div>
        <Link to="/store" className="flex items-center gap-3 bg-white text-black px-8 py-4 rounded-full font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all hover:scale-105 shadow-xl">
          <ShoppingBag size={18} /> Shop All Merch
        </Link>
      </div>
      <div className="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        {items.map((item, i) => (
          <motion.div
            key={i}
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ delay: i * 0.1 }}
            viewport={{ once: true }}
            className="group cursor-pointer"
          >
            <div className="aspect-[4/5] rounded-3xl overflow-hidden bg-[#111] mb-6 border border-white/5 relative shadow-md group-hover:shadow-xl transition-all">
              <img src={item.img} alt={item.title} className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-70 group-hover:opacity-100" />
              <div className="absolute top-4 right-4 bg-black/80 backdrop-blur-md p-3 rounded-full opacity-0 group-hover:opacity-100 transition-all translate-y-2 group-hover:translate-y-0">
                <ShoppingBag size={16} className="text-white" />
              </div>
            </div>
            <h4 className="font-bold mb-1 tracking-tight text-white uppercase text-sm">{item.title}</h4>
            <p className="text-blue-500 font-black text-sm">{item.price}</p>
          </motion.div>
        ))}
      </div>
    </section>
  );
};

const NewsletterSection = () => {
  const [email, setEmail] = useState('');
  const [status, setStatus] = useState<'idle' | 'loading' | 'success' | 'error'>('idle');
  const [msg, setMsg] = useState('');

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    if (!email) return;
    setStatus('loading');
    try {
      // Import db from firebase lib if needed, but since this is in App.tsx we might need to handle imports
      // Actually Newsletter component I created earlier is better. I will just use that component.
      // But for now I'll just fix this one.
      const { collection, addDoc, serverTimestamp, query, where, getDocs } = await import('firebase/firestore');
      const { db } = await import('./lib/firebase');

      const q = query(collection(db, 'subscribers'), where('email', '==', email.toLowerCase()));
      const snap = await getDocs(q);
      if (!snap.empty) {
        setMsg('Already subscribed!');
        setStatus('error');
        return;
      }

      await addDoc(collection(db, 'subscribers'), {
        email: email.toLowerCase(),
        subscribedAt: serverTimestamp(),
        active: true
      });
      setStatus('success');
      setMsg('Subscribed successfully!');
      setEmail('');
    } catch (err) {
      console.error(err);
      setStatus('error');
      setMsg('Error subscribing.');
    }
  };

  return (
    <section className="py-24 px-6 bg-black overflow-hidden relative">
      <div className="absolute inset-0 bg-[#0a0a0a]" />
      <div className="max-w-7xl mx-auto relative z-10">
        <div className="bg-gradient-to-br from-blue-600 to-blue-800 rounded-[80px] p-10 md:p-24 text-center shadow-5xl relative overflow-hidden group border border-white/5">
          <div className="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10" />
          <motion.div
            initial={{ opacity: 0, scale: 0.9 }}
            whileInView={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.8 }}
            viewport={{ once: true }}
          >
            <span className="text-white/40 font-black uppercase tracking-[0.4em] text-[10px] mb-6 block italic">STAY CONNECTED</span>
            <h2 className="text-5xl md:text-7xl font-black tracking-tighter text-white mb-8 italic uppercase leading-none">The Newsletter.</h2>
            <p className="text-white/70 max-w-xl mx-auto mb-12 text-lg font-medium leading-relaxed">
              Join our weekly community to receive spiritual inspiration, updates on events, and words of encouragement.
            </p>
            <form className="flex flex-col sm:flex-row gap-4 max-w-2xl mx-auto" onSubmit={handleSubmit}>
              <input
                type="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="YOUR EMAIL ADDRESS"
                className="flex-1 bg-white/10 backdrop-blur-xl border border-white/20 rounded-full px-10 py-5 text-white text-sm font-black placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-white transition-all shadow-xl"
              />
              <button
                disabled={status === 'loading'}
                className="bg-white text-black px-12 py-5 rounded-full font-black text-[11px] uppercase tracking-widest hover:bg-gray-100 transition-all flex items-center justify-center gap-3 shadow-2xl active:scale-95 disabled:opacity-50"
              >
                {status === 'loading' ? 'WAITING...' : 'Subscribe Now'} <ArrowRight size={16} className="stroke-[3px]" />
              </button>
            </form>
            {msg && <p className={`mt-4 text-[10px] font-black uppercase tracking-widest ${status === 'success' ? 'text-green-400' : 'text-red-400'}`}>{msg}</p>}
          </motion.div>
        </div>
      </div>
    </section>
  );
};

const GivingSection = () => {
  const [showDonationModal, setShowDonationModal] = useState(false);
  const [amount, setAmount] = useState('500');
  const [customAmount, setCustomAmount] = useState('');

  const UPI_ID = 'qr.aakhikh@sib';
  const ACCOUNT_NAME = 'AAKHIKH HOUSE OF BLESSINGS';

  const handleDonate = () => {
    const finalAmount = customAmount || amount;
    // UPI format: upi://pay?pa=VPA&pn=NAME&am=AMOUNT&cu=INR&tn=Donation
    const upiUrl = `upi://pay?pa=${UPI_ID}&pn=${encodeURIComponent(ACCOUNT_NAME)}&am=${finalAmount}&cu=INR&tn=Donation%20to%20AAKHIKH`;

    // Attempt to open UPI app
    window.location.href = upiUrl;
  };

  return (
    <section id="giving" className="py-24 px-6 bg-gray-50 overflow-hidden">
      <div className="max-w-7xl mx-auto">
        <div className="text-center mb-20">
          <span className="text-blue-600 font-black uppercase tracking-[0.4em] text-[10px] mb-4 block italic">SUPPORT OUR MISSION</span>
          <h2 className="text-7xl font-black tracking-tighter mb-6 italic text-black uppercase">Generosity.</h2>
          <p className="text-gray-400 max-w-xl mx-auto font-medium">Your support enables us to reach the community and build a house of blessings for all.</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-10">
          <motion.div
            initial={{ opacity: 0, x: -30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            className="bg-white p-12 rounded-[50px] shadow-4xl group hover:shadow-5xl transition-all"
          >
            <div className="flex items-center gap-5 mb-12">
              <div className="p-5 bg-blue-50 text-blue-600 rounded-[24px] group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                <Heart size={36} fill="currentColor" className="fill-blue-600 group-hover:fill-white" />
              </div>
              <div>
                <h4 className="text-gray-300 font-black text-[10px] uppercase tracking-[0.3em] italic mb-1">OPTION 01</h4>
                <p className="text-black font-black text-2xl uppercase italic tracking-tighter leading-none">Bank Transfer</p>
              </div>
            </div>

            <div className="space-y-8 pt-8 border-t border-gray-100 italic">
              <div>
                <p className="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1.5 opacity-50">BANK NAME</p>
                <p className="text-2xl font-black text-blue-600 uppercase tracking-tighter">State Bank of India</p>
              </div>
              <div className="grid grid-cols-2 gap-10">
                <div>
                  <p className="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1.5 opacity-50">ACCOUNT NO.</p>
                  <p className="text-xl font-black text-black tabular-nums tracking-tighter">42817454868</p>
                </div>
                <div>
                  <p className="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1.5 opacity-50">IFSC CODE</p>
                  <p className="text-xl font-black text-black tabular-nums tracking-tighter">SBIN0016335</p>
                </div>
              </div>
              <div>
                <p className="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1.5 opacity-50">ACCOUNT HOLDER</p>
                <p className="text-lg font-black text-black uppercase tracking-tight">AAKHIKH HOUSE OF BLESSINGS</p>
              </div>
            </div>
          </motion.div>

          <motion.div
            initial={{ opacity: 0, x: 30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            className="bg-[#1133aa] p-12 rounded-[50px] shadow-4xl text-white relative overflow-hidden"
          >
            <div className="relative z-10 h-full flex flex-col">
              <div className="flex items-center gap-5 mb-12">
                <div className="p-5 bg-white/10 text-white rounded-[24px]">
                  <Play size={36} fill="white" />
                </div>
                <div>
                  <h4 className="text-white/40 font-black text-[10px] uppercase tracking-[0.3em] italic mb-1">OPTION 02</h4>
                  <p className="text-white font-black text-2xl uppercase italic tracking-tighter leading-none">Give Online</p>
                </div>
              </div>

              <div className="mt-auto">
                <h3 className="text-5xl md:text-6xl font-black italic uppercase tracking-tighter mb-12 leading-[0.9]">UPI / Scan <br />to Give.</h3>
                <button
                  onClick={() => setShowDonationModal(true)}
                  className="w-full bg-white text-[#1133aa] py-5 rounded-full font-black text-xs uppercase tracking-widest hover:bg-gray-100 transition-all flex items-center justify-center gap-3 shadow-2xl active:scale-95"
                >
                  Donate Now <ArrowRight size={18} className="stroke-[3px]" />
                </button>
              </div>
            </div>
            <div className="absolute -bottom-20 -right-20 w-80 h-80 bg-white/5 rounded-full blur-3xl" />
          </motion.div>
        </div>
      </div>

      <AnimatePresence>
        {showDonationModal && (
          <div className="fixed inset-0 z-[100] flex items-center justify-center p-6">
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              onClick={() => setShowDonationModal(false)}
              className="absolute inset-0 bg-black/80 backdrop-blur-md"
            />
            <motion.div
              initial={{ opacity: 0, scale: 0.9, y: 20 }}
              animate={{ opacity: 1, scale: 1, y: 0 }}
              exit={{ opacity: 0, scale: 0.9, y: 20 }}
              className="relative w-full max-w-lg bg-white rounded-[40px] overflow-hidden shadow-6xl"
            >
              <div className="p-10">
                <div className="flex justify-between items-start mb-8">
                  <div>
                    <h3 className="text-3xl font-black italic uppercase tracking-tighter text-black leading-none mb-2">Secure Giving.</h3>
                    <p className="text-gray-400 text-xs font-bold uppercase tracking-widest">Select your donation amount</p>
                  </div>
                  <button
                    onClick={() => setShowDonationModal(false)}
                    className="p-3 bg-gray-100 rounded-full hover:bg-gray-200 transition-all"
                  >
                    <X size={20} className="text-black" />
                  </button>
                </div>

                <div className="grid grid-cols-3 gap-4 mb-8">
                  {['200', '500', '1000'].map((amt) => (
                    <button
                      key={amt}
                      onClick={() => {
                        setAmount(amt);
                        setCustomAmount('');
                      }}
                      className={`py-4 rounded-2xl font-black transition-all ${amount === amt && !customAmount
                        ? 'bg-blue-600 text-white shadow-xl scale-105'
                        : 'bg-gray-50 text-black hover:bg-gray-100 border border-gray-100'
                        }`}
                    >
                      ₹{amt}
                    </button>
                  ))}
                </div>

                <div className="relative mb-10">
                  <span className="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-black">₹</span>
                  <input
                    type="number"
                    placeholder="Enter custom amount"
                    value={customAmount}
                    onChange={(e) => setCustomAmount(e.target.value)}
                    className="w-full bg-gray-50 border border-gray-100 rounded-2xl py-5 pl-12 pr-6 text-black font-black focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all"
                  />
                </div>

                <div className="space-y-4">
                  <button
                    onClick={handleDonate}
                    className="w-full bg-[#1133aa] text-white py-6 rounded-full font-black text-sm uppercase tracking-widest hover:bg-blue-700 transition-all shadow-2xl flex items-center justify-center gap-3 active:scale-95"
                  >
                    Pay with UPI <Smartphone size={20} />
                  </button>
                  <p className="text-center text-[10px] text-gray-400 font-bold uppercase tracking-widest italic flex items-center justify-center gap-2">
                    <CheckCircle2 size={12} className="text-green-500" /> Secure Encryption • Instant Transfer
                  </p>
                </div>
              </div>

              <div className="bg-gray-50 p-8 border-t border-gray-100 flex items-center gap-4">
                <div className="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center border border-gray-100">
                  <Smartphone size={24} className="text-blue-600" />
                </div>
                <div>
                  <p className="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-0.5">Payment Method</p>
                  <p className="text-xs font-black text-black">Any UPI App (GPay, PhonePe, Paytm)</p>
                </div>
              </div>
            </motion.div>
          </div>
        )}
      </AnimatePresence>
    </section>
  );
};

const ContactFooter = () => {
  return (
    <footer id="contact" className="bg-[#050505] text-white py-32 px-6 overflow-hidden">
      <div className="max-w-7xl mx-auto">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-20 mb-32">
          <div className="lg:col-span-1">
            <div className="flex items-center gap-3 mb-10">
              <div className="w-12 h-12 rounded-full bg-white flex items-center justify-center font-black text-black text-2xl shadow-2xl transition-transform hover:rotate-12 cursor-pointer">
                A
              </div>
              <span className="font-black tracking-[0.2em] text-2xl italic uppercase">
                AAKHIKH
              </span>
            </div>
            <p className="text-gray-500 text-base leading-relaxed mb-12 font-medium max-w-sm">
              A community of faith spreading hope and building faith across North-Eastern India. Join us in the journey of grace and divine transformation.
            </p>
            <div className="flex gap-6">
              <a href="#" className="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center hover:bg-blue-600 transition-all hover:scale-110 shadow-xl border border-white/5">
                <Facebook size={20} />
              </a>
              <a href="#" className="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center hover:bg-blue-600 transition-all hover:scale-110 shadow-xl border border-white/5">
                <Instagram size={20} />
              </a>
              <a href="#" className="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center hover:bg-blue-600 transition-all hover:scale-110 shadow-xl border border-white/5">
                <Youtube size={20} />
              </a>
            </div>
          </div>

          <div>
            <h4 className="text-[10px] font-black uppercase tracking-[0.4em] text-blue-600 mb-12 italic tracking-[0.3em]">GET IN TOUCH</h4>
            <ul className="space-y-8 italic">
              <li className="flex items-start gap-5 group">
                <MapPin className="text-blue-600 shrink-0 mt-1 transition-transform group-hover:scale-110" size={22} />
                <span className="text-sm text-gray-400 font-medium leading-relaxed tracking-tight">Shillong, Meghalaya <br />North East India</span>
              </li>
              <li className="flex items-center gap-5 group">
                <Mail className="text-blue-600 shrink-0 transition-transform group-hover:scale-110" size={22} />
                <span className="text-sm text-gray-400 font-medium">contact@aakhikh.org</span>
              </li>
              <li className="flex items-center gap-5 group">
                <Phone className="text-blue-600 shrink-0 transition-transform group-hover:scale-110" size={22} />
                <span className="text-sm text-gray-400 font-medium tabular-nums">+91 81339 12123</span>
              </li>
            </ul>
          </div>

          <div>
            <h4 className="text-[10px] font-black uppercase tracking-[0.4em] text-blue-600 mb-12 italic tracking-[0.3em]">QUICK LINKS</h4>
            <ul className="grid grid-cols-1 gap-5 italic">
              {['Home', 'Our History', 'Beliefs', 'Leadership', 'Events', 'Sermons', 'Giving'].map((item) => (
                <li key={item}>
                  <a href={`#${item.toLowerCase().replace(' ', '')}`} className="text-sm text-gray-500 hover:text-white transition-colors font-black tracking-[0.1em] uppercase group flex items-center gap-2">
                    <span className="w-0 h-[2px] bg-blue-600 group-hover:w-4 transition-all" /> {item}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h4 className="text-[10px] font-black uppercase tracking-[0.4em] text-blue-600 mb-12 italic tracking-[0.3em]">HOUSE OF BLESSINGS</h4>
            <p className="text-gray-500 text-sm font-medium mb-10 leading-relaxed max-w-xs">
              Join our family and experience the power of communal faith and transformation.
            </p>
            <div className="relative">
              <input
                type="email"
                placeholder="Enter your email"
                className="w-full bg-white/5 border border-white/10 rounded-full px-8 py-5 text-sm focus:outline-none focus:border-blue-600 transition-colors italic font-bold placeholder:opacity-50"
              />
              <button className="absolute right-2 top-2 bottom-2 bg-blue-600 px-8 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-xl">
                JOIN
              </button>
            </div>
          </div>
        </div>

        <div className="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-8">
          <p className="text-gray-600 text-[10px] uppercase font-black tracking-[0.3em] italic">
            © 2024 AAKHIKH MINISTRIES. ALL RIGHTS RESERVED.
          </p>
          <div className="flex gap-10 text-[10px] uppercase font-black tracking-[0.3em] text-gray-600 italic">
            <a href="#" className="hover:text-white transition-colors">Privacy</a>
            <a href="#" className="hover:text-white transition-colors">Terms</a>
          </div>
        </div>
      </div>
    </footer>
  );
};

const LandingPage = () => (
  <>
    <Hero />
    <ScrollingTicker />
    <OurStorySection />
    <MinistryGrid />
    <LeadershipSection />
    <BeliefsSection />
    <DailyBread />
    <UpcomingEvents />
    <YouTubeSection />
    <SermonExperience />
    <TestimoniesSection />
    <StorePreview />
    <GivingSection />
    <NewsletterSection />
  </>
);

export default function App() {
  return (
    <Router>
      <CartProvider>
        <main className="bg-black min-h-screen font-sans selection:bg-blue-600 selection:text-white">
          <Navbar />
          <Routes>
            <Route path="/" element={<LandingPage />} />
            <Route path="/store" element={<Store />} />
            <Route path="/admin" element={<Admin />} />
          </Routes>
          <ContactFooter />
        </main>
      </CartProvider>
    </Router>
  );
}
