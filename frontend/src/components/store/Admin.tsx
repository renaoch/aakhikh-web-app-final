import React, { useEffect, useState } from 'react';
import { 
  collection, 
  getDocs, 
  addDoc, 
  updateDoc, 
  deleteDoc, 
  doc, 
  orderBy, 
  query, 
  serverTimestamp 
} from 'firebase/firestore';
import { db, auth, handleFirestoreError, OperationType } from '../../lib/firebase';
import { Product, Order } from '../../types/store';
import { Plus, Trash2, Edit3, X, Loader2, Package, Tag, IndianRupee, Image as ImageIcon, BarChart3, Clock, CheckCircle2, Truck, Mail, Send } from 'lucide-react';
import { motion, AnimatePresence } from 'motion/react';

const ADMIN_EMAIL = 'chetri.prem999@gmail.com'; // hardcoded

const Admin: React.FC = () => {
  const [products, setProducts] = useState<Product[]>([]);
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingProduct, setEditingProduct] = useState<Product | null>(null);
  const [activeTab, setActiveTab] = useState<'products' | 'orders' | 'newsletter'>('products');
  const [subscribers, setSubscribers] = useState<{id: string, email: string}[]>([]);

  // Newsletter Form
  const [emailSubject, setEmailSubject] = useState('');
  const [emailBody, setEmailBody] = useState('');
  const [isSending, setIsSending] = useState(false);
  const [formData, setFormData] = useState({
    name: '',
    description: '',
    price: 0,
    image: '',
    category: 'Clothing',
    stock: 0
  });

  useEffect(() => {
    if (auth.currentUser?.email?.toLowerCase() === ADMIN_EMAIL.toLowerCase()) {
      fetchData();
    }
  }, []);

  const fetchData = async () => {
    setLoading(true);
    try {
      const pQ = query(collection(db, 'products'), orderBy('createdAt', 'desc'));
      const oQ = query(collection(db, 'orders'), orderBy('createdAt', 'desc'));
      const sQ = query(collection(db, 'subscribers'), orderBy('subscribedAt', 'desc'));
      
      const [pSnap, oSnap, sSnap] = await Promise.all([getDocs(pQ), getDocs(oQ), getDocs(sQ)]);
      
      setProducts(pSnap.docs.map(doc => ({ id: doc.id, ...doc.data() } as Product)));
      setOrders(oSnap.docs.map(doc => ({ id: doc.id, ...doc.data() } as Order)));
      setSubscribers(sSnap.docs.map(doc => ({ id: doc.id, ...doc.data() } as any)));
    } catch (error) {
      handleFirestoreError(error, OperationType.LIST, 'admin-data');
    } finally {
      setLoading(false);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      if (editingProduct) {
        const ref = doc(db, 'products', editingProduct.id);
        await updateDoc(ref, { ...formData });
      } else {
        await addDoc(collection(db, 'products'), {
          ...formData,
          createdAt: serverTimestamp()
        });
      }
      setIsModalOpen(false);
      setEditingProduct(null);
      setFormData({ name: '', description: '', price: 0, image: '', category: 'Clothing', stock: 0 });
      fetchData();
    } catch (error) {
      handleFirestoreError(error, OperationType.WRITE, 'products');
    }
  };

  const handleDelete = async (id: string) => {
    if (!window.confirm('Delete this product?')) return;
    try {
      await deleteDoc(doc(db, 'products', id));
      fetchData();
    } catch (error) {
      handleFirestoreError(error, OperationType.DELETE, 'products');
    }
  };

  const updateOrderStatus = async (id: string, status: string) => {
    try {
      await updateDoc(doc(db, 'orders', id), { status });
      fetchData();
    } catch (error) {
      handleFirestoreError(error, OperationType.UPDATE, 'orders');
    }
  };

  if (auth.currentUser?.email?.toLowerCase() !== ADMIN_EMAIL.toLowerCase()) {
    return (
      <div className="min-h-screen bg-black flex items-center justify-center text-center p-6">
        <div className="max-w-md">
          <h2 className="text-4xl font-black italic uppercase tracking-tighter text-white mb-6">Restricted Area</h2>
          <p className="text-gray-500 font-medium italic">You must be logged in as an administrator to access this panel.</p>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-[#050505] text-white pt-32 pb-24 px-6">
      <div className="max-w-7xl mx-auto">
        <div className="flex flex-col md:flex-row items-end justify-between mb-16 gap-8">
          <div>
            <span className="text-blue-500 font-bold uppercase tracking-[0.3em] text-xs mb-4 block italic">Administration</span>
            <h1 className="text-5xl md:text-7xl font-black tracking-tighter italic leading-none uppercase">Store Control.</h1>
          </div>
          <div className="flex bg-white/5 rounded-full p-2 border border-white/5">
             <button 
               onClick={() => setActiveTab('products')}
               className={`px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-widest transition-all ${activeTab === 'products' ? 'bg-white text-black' : 'text-white/50 hover:text-white'}`}
             >
               Inventory
             </button>
             <button 
               onClick={() => setActiveTab('orders')}
               className={`px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-widest transition-all ${activeTab === 'orders' ? 'bg-white text-black' : 'text-white/50 hover:text-white'}`}
             >
               Order Log
             </button>
             <button 
               onClick={() => setActiveTab('newsletter')}
               className={`px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-widest transition-all ${activeTab === 'newsletter' ? 'bg-white text-black' : 'text-white/50 hover:text-white'}`}
             >
               Newsletter
             </button>
          </div>
        </div>

        {activeTab === 'products' ? (
          <div>
            <div className="flex justify-between items-center mb-10">
              <h3 className="text-2xl font-black italic uppercase tracking-tighter">Product Inventory</h3>
              <button 
                onClick={() => { setEditingProduct(null); setIsModalOpen(true); }}
                className="bg-blue-600 px-8 py-4 rounded-full font-black text-[10px] uppercase tracking-widest flex items-center gap-2 hover:bg-blue-700 transition-all shadow-xl"
              >
                <Plus size={16} /> Add Product
              </button>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {products.map(p => (
                <div key={p.id} className="bg-white/5 border border-white/5 rounded-[32px] p-8 flex gap-6 hover:border-white/10 transition-colors group">
                  <div className="w-24 h-24 rounded-2xl bg-[#111] overflow-hidden shrink-0">
                    <img src={p.image} alt={p.name} className="w-full h-full object-cover" />
                  </div>
                  <div className="flex-1">
                    <div className="flex justify-between items-start mb-2">
                       <h4 className="font-black text-sm uppercase tracking-tight italic">{p.name}</h4>
                       <div className="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                         <button 
                           onClick={() => { setEditingProduct(p); setFormData({ ...p }); setIsModalOpen(true); }}
                           className="p-2 hover:bg-blue-600 rounded-full transition-colors"
                         ><Edit3 size={14} /></button>
                         <button 
                           onClick={() => handleDelete(p.id)}
                           className="p-2 hover:bg-red-600 rounded-full transition-colors"
                         ><Trash2 size={14} /></button>
                       </div>
                    </div>
                    <p className="text-blue-500 font-black text-lg italic tracking-tighter mb-4">₹{p.price}</p>
                    <div className="flex items-center gap-3">
                       <span className={`text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest ${p.stock > 0 ? 'bg-green-500/20 text-green-500' : 'bg-red-500/20 text-red-500'}`}>
                         Stock: {p.stock}
                       </span>
                       <span className="text-[10px] font-black px-3 py-1 bg-white/5 rounded-full uppercase tracking-widest text-white/40">
                         {p.category}
                       </span>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        ) : activeTab === 'orders' ? (
          <div className="space-y-6">
             {orders.map(order => (
               <div key={order.id} className="bg-white/5 border border-white/5 rounded-[40px] p-10 overflow-hidden relative group">
                 <div className="flex flex-col lg:flex-row gap-12">
                   <div className="flex-1">
                     <div className="flex items-center gap-4 mb-8">
                       <div className={`w-3 h-3 rounded-full animate-pulse ${order.status === 'paid' ? 'bg-green-500' : order.status === 'shipped' ? 'bg-blue-500' : 'bg-yellow-500'}`} />
                       <span className="text-[10px] font-black uppercase tracking-[.3em] text-white/40">Order ID: {order.id.slice(0, 8)}</span>
                       <span className="text-gray-600 text-[10px] ml-auto font-black">{order.createdAt instanceof Date ? order.createdAt.toLocaleDateString() : (order.createdAt as any)?.toDate().toLocaleDateString()}</span>
                     </div>
                     <h4 className="text-2xl font-black italic uppercase tracking-tighter mb-8">{order.customerName}</h4>
                     <div className="space-y-4 mb-10">
                        {order.items.map((item, idx) => (
                          <div key={idx} className="flex justify-between items-center text-sm font-medium text-gray-500 border-b border-white/5 pb-2">
                            <span>{item.name} × {item.quantity}</span>
                            <span className="text-white">₹{item.price * item.quantity}</span>
                          </div>
                        ))}
                     </div>
                     <div className="flex items-end justify-between">
                        <div>
                          <p className="text-[10px] font-black uppercase text-gray-600 tracking-widest mb-1">Email</p>
                          <p className="text-sm font-bold text-blue-500">{order.customerEmail}</p>
                        </div>
                        <div className="text-right">
                          <p className="text-[10px] font-black uppercase text-gray-600 tracking-widest mb-1">Total Paid</p>
                          <p className="text-3xl font-black italic tracking-tighter">₹{order.totalAmount}</p>
                        </div>
                     </div>
                   </div>
                   
                   <div className="lg:w-64 bg-white/5 p-8 rounded-3xl flex flex-col justify-center gap-4">
                      <p className="text-[10px] font-black uppercase text-center text-white/40 mb-2 tracking-widest">Update Progress</p>
                      <button 
                        onClick={() => updateOrderStatus(order.id, 'paid')}
                        className={`w-full py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2 ${order.status === 'paid' ? 'bg-green-500 text-white' : 'bg-white/5 hover:bg-white/10'}`}
                      >
                        <CheckCircle2 size={14} /> Paid
                      </button>
                      <button 
                        onClick={() => updateOrderStatus(order.id, 'shipped')}
                        className={`w-full py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2 ${order.status === 'shipped' ? 'bg-blue-600 text-white' : 'bg-white/5 hover:bg-white/10'}`}
                      >
                        <Truck size={14} /> Shipped
                      </button>
                      <button 
                        onClick={() => updateOrderStatus(order.id, 'cancelled')}
                        className={`w-full py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2 ${order.status === 'cancelled' ? 'bg-red-600 text-white' : 'bg-white/5 hover:bg-white/10'}`}
                      >
                        <X size={14} /> Cancel
                      </button>
                   </div>
                 </div>
               </div>
             ))}
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div className="bg-white/5 border border-white/5 rounded-[40px] p-12">
              <h3 className="text-2xl font-black italic uppercase tracking-tighter mb-8 flex items-center gap-4">
                <Mail size={24} className="text-blue-500" /> Compose Newsletter
              </h3>
              <div className="space-y-6">
                <div>
                  <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest mb-3 block">Email Subject</label>
                  <input 
                    type="text" 
                    value={emailSubject}
                    onChange={(e) => setEmailSubject(e.target.value)}
                    placeholder="E.g. Sunday Service Update"
                    className="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm focus:outline-none focus:border-blue-600 transition-colors"
                  />
                </div>
                <div>
                  <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest mb-3 block">Email Body (HTML Supported)</label>
                  <textarea 
                    rows={10}
                    value={emailBody}
                    onChange={(e) => setEmailBody(e.target.value)}
                    placeholder="Write your newsletter message here..."
                    className="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm focus:outline-none focus:border-blue-600 transition-colors resize-none font-mono"
                  />
                </div>
                <button 
                  onClick={async () => {
                    if (!emailSubject || !emailBody) return alert('Please fill in subject and body');
                    if (subscribers.length === 0) return alert('No subscribers to send to');
                    
                    setIsSending(true);
                    try {
                      const response = await fetch('/api/send-newsletter', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                          recipients: subscribers.map(s => s.email),
                          subject: emailSubject,
                          body: emailBody,
                          isHtml: true
                        })
                      });
                      const data = await response.json();
                      if (data.success) {
                        alert('Newsletter sent successfully!');
                        setEmailSubject('');
                        setEmailBody('');
                      } else {
                        alert('Error: ' + data.error);
                      }
                    } catch (err) {
                      console.error(err);
                      alert('Failed to send newsletter');
                    } finally {
                      setIsSending(false);
                    }
                  }}
                  disabled={isSending}
                  className="w-full bg-blue-600 text-white py-5 rounded-3xl font-black text-[11px] uppercase tracking-widest hover:bg-blue-700 transition-all shadow-2xl flex items-center justify-center gap-3 disabled:opacity-50"
                >
                  {isSending ? <Loader2 className="animate-spin" /> : <Send size={16} />} 
                  {isSending ? 'Sending to Subscribers...' : `Send to ${subscribers.length} Subscribers`}
                </button>
              </div>
            </div>

            <div className="bg-white/5 border border-white/5 rounded-[40px] p-12">
              <h3 className="text-2xl font-black italic uppercase tracking-tighter mb-8">Subscriber List</h3>
              <div className="max-h-[500px] overflow-y-auto space-y-4 pr-2">
                {subscribers.map((s, i) => (
                  <div key={s.id} className="flex justify-between items-center p-4 bg-white/5 rounded-2xl border border-white/5">
                    <span className="text-sm font-bold">{s.email}</span>
                    <span className="text-[10px] text-gray-500 font-black uppercase">#{i + 1}</span>
                  </div>
                ))}
              </div>
            </div>
          </div>
        )}
      </div>

      <AnimatePresence>
        {isModalOpen && (
          <>
            <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }} onClick={() => setIsModalOpen(false)} className="fixed inset-0 bg-black/90 backdrop-blur-md z-[100]" />
            <motion.div 
              initial={{ scale: 0.9, opacity: 0 }} 
              animate={{ scale: 1, opacity: 1 }} 
              exit={{ scale: 0.9, opacity: 0 }}
              className="fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl bg-[#0a0a0a] border border-white/10 rounded-[40px] z-[101] overflow-hidden"
            >
              <form onSubmit={handleSubmit} className="p-12 space-y-8">
                 <div className="flex items-center justify-between">
                    <h2 className="text-3xl font-black italic uppercase tracking-tighter">{editingProduct ? 'Edit Identity' : 'New Product'}</h2>
                    <button type="button" onClick={() => setIsModalOpen(false)}><X /></button>
                 </div>
                 
                 <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div className="space-y-2">
                       <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest flex items-center gap-2"><Tag size={12}/> Product Name</label>
                       <input required type="text" value={formData.name} onChange={e => setFormData({...formData, name: e.target.value})} className="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm focus:outline-none focus:border-blue-600 transition-colors" />
                    </div>
                    <div className="space-y-2">
                       <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest flex items-center gap-2"><IndianRupee size={12}/> Price (INR)</label>
                       <input required type="number" value={formData.price} onChange={e => setFormData({...formData, price: Number(e.target.value)})} className="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm focus:outline-none focus:border-blue-600 transition-colors" />
                    </div>
                    <div className="space-y-2">
                       <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest flex items-center gap-2"><ImageIcon size={12}/> Image URL</label>
                       <input required type="url" value={formData.image} onChange={e => setFormData({...formData, image: e.target.value})} className="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm focus:outline-none focus:border-blue-600 transition-colors" />
                    </div>
                    <div className="space-y-2">
                       <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest flex items-center gap-2"><Package size={12}/> Initial Stock</label>
                       <input required type="number" value={formData.stock} onChange={e => setFormData({...formData, stock: Number(e.target.value)})} className="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm focus:outline-none focus:border-blue-600 transition-colors" />
                    </div>
                    <div className="md:col-span-2 space-y-2">
                       <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest flex items-center gap-2"><Clock size={12}/> Category</label>
                       <select value={formData.category} onChange={e => setFormData({...formData, category: e.target.value})} className="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm focus:outline-none focus:border-blue-600 transition-colors appearance-none">
                          <option value="Clothing">Clothing</option>
                          <option value="Accessories">Accessories</option>
                          <option value="Books">Books</option>
                          <option value="Home">Home</option>
                       </select>
                    </div>
                    <div className="md:col-span-2 space-y-2">
                       <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest">Description</label>
                       <textarea required rows={4} value={formData.description} onChange={e => setFormData({...formData, description: e.target.value})} className="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm focus:outline-none focus:border-blue-600 transition-colors resize-none" />
                    </div>
                 </div>
                 
                 <button type="submit" className="w-full bg-white text-black py-5 rounded-3xl font-black text-[11px] uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all shadow-2xl">
                    {editingProduct ? 'Update Product' : 'Add to Collection'}
                 </button>
              </form>
            </motion.div>
          </>
        )}
      </AnimatePresence>
    </div>
  );
};

export default Admin;
