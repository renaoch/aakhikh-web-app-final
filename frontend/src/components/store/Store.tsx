import React, { useEffect, useState } from 'react';
import { collection, getDocs, orderBy, query, addDoc, serverTimestamp } from 'firebase/firestore';
import { db, handleFirestoreError, OperationType, auth } from '../../lib/firebase';
import { Product } from '../../types/store';
import { motion, AnimatePresence } from 'motion/react';
import { ShoppingBag, X, Plus, Minus, ArrowRight, Loader2 } from 'lucide-react';
import { useCart } from '../../context/CartContext';

const Store: React.FC = () => {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [isCartOpen, setIsCartOpen] = useState(false);
  const { cart, addToCart, removeFromCart, updateQuantity, total, clearCart } = useCart();
  const [isCheckingOut, setIsCheckingOut] = useState(false);

  useEffect(() => {
    fetchProducts();
  }, []);

  const fetchProducts = async () => {
    try {
      const q = query(collection(db, 'products'), orderBy('createdAt', 'desc'));
      const snapshot = await getDocs(q);
      const data = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() } as Product));
      setProducts(data);
    } catch (error) {
      handleFirestoreError(error, OperationType.LIST, 'products');
    } finally {
      setLoading(false);
    }
  };

  const handleCheckout = async () => {
    if (!auth.currentUser) {
      alert("Please sign in to place an order");
      return;
    }

    if (cart.length === 0) return;

    setIsCheckingOut(true);
    try {
      // In a real app, you'd call a backend to create a Razorpay order
      // Here we simulate the process on FE as requested "FE only"
      
      const options = {
        key: 'rzp_test_YOUR_KEY_HERE', // This won't work without a real key, but it's for demo
        amount: total * 100,
        currency: 'INR',
        name: 'AAKHIKH Store',
        description: 'Merchandise Purchase',
        handler: async function (response: any) {
          // Payment successful
          try {
            const orderData = {
              customerEmail: auth.currentUser?.email,
              customerName: auth.currentUser?.displayName,
              items: cart,
              totalAmount: total,
              status: 'paid',
              razorpayPaymentId: response.razorpay_payment_id,
              createdAt: serverTimestamp(),
            };
            await addDoc(collection(db, 'orders'), orderData);
            clearCart();
            setIsCartOpen(false);
            alert("Payment successful! Order placed.");
          } catch (e) {
            handleFirestoreError(e, OperationType.CREATE, 'orders');
          }
        },
        prefill: {
          name: auth.currentUser.displayName,
          email: auth.currentUser.email,
        },
        theme: {
          color: '#1133aa',
        },
      };

      const rzp = new (window as any).Razorpay(options);
      rzp.open();
    } catch (error) {
      console.error(error);
      alert("Checkout failed. Please try again.");
    } finally {
      setIsCheckingOut(false);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-black flex items-center justify-center">
        <Loader2 className="w-12 h-12 text-blue-600 animate-spin" />
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-[#050505] text-white pt-32 pb-24 px-6 relative">
      <div className="max-w-7xl mx-auto">
        <div className="flex flex-col md:flex-row items-end justify-between mb-16 gap-8">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
          >
            <span className="text-blue-500 font-bold uppercase tracking-[0.3em] text-xs mb-4 block italic">Merchandise</span>
            <h1 className="text-5xl md:text-7xl font-black tracking-tighter italic leading-none uppercase text-white">THE BLESSING <br />COLLECTION.</h1>
          </motion.div>
          <button 
            onClick={() => setIsCartOpen(true)}
            className="relative p-5 bg-white text-black rounded-full shadow-2xl hover:scale-105 transition-all group"
          >
            <ShoppingBag size={24} />
            {cart.length > 0 && (
              <span className="absolute -top-1 -right-1 bg-blue-600 text-white text-[10px] font-black w-6 h-6 rounded-full flex items-center justify-center border-4 border-black">
                {cart.length}
              </span>
            )}
          </button>
        </div>

        {products.length === 0 ? (
          <div className="py-32 text-center border border-dashed border-white/10 rounded-[40px]">
            <p className="text-gray-500 font-medium italic">No products available in the store yet.</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
            {products.map((product, i) => (
              <motion.div 
                key={product.id}
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: i * 0.05 }}
                className="group flex flex-col bg-white/[0.02] border border-white/5 rounded-[40px] overflow-hidden hover:border-blue-600/30 transition-all duration-500 hover:shadow-[0_20px_50px_rgba(37,99,235,0.1)]"
              >
                <div className="relative aspect-[4/5] overflow-hidden bg-[#111]">
                  <img 
                    src={product.image} 
                    alt={product.name} 
                    className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100" 
                  />
                  <div className="absolute top-6 right-6">
                    <span className="bg-white/10 backdrop-blur-md text-white text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest border border-white/10">
                      {product.category}
                    </span>
                  </div>
                </div>
                
                <div className="p-8 flex flex-col flex-1">
                  <div className="flex justify-between items-start mb-2">
                    <h3 className="font-black text-xl text-white uppercase tracking-tight italic leading-none">{product.name}</h3>
                    <p className="text-blue-500 font-black text-2xl italic tracking-tighter">₹{product.price.toLocaleString()}</p>
                  </div>
                  <p className="text-gray-500 text-xs mb-8 line-clamp-2 h-8">{product.description}</p>
                  
                  <div className="flex flex-col gap-3 mt-auto">
                    <button 
                      onClick={() => {
                        addToCart(product);
                        setIsCartOpen(true);
                      }}
                      className="w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-white hover:text-black transition-all shadow-xl flex items-center justify-center gap-2"
                    >
                      Buy Now
                    </button>
                    <button 
                      onClick={() => addToCart(product)}
                      className="w-full bg-white/5 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest border border-white/10 hover:bg-white/10 transition-all flex items-center justify-center gap-2"
                    >
                      <ShoppingBag size={14} /> Add to Cart
                    </button>
                  </div>
                </div>
              </motion.div>
            ))}
          </div>
        )}
      </div>

      {/* Cart Drawer */}
      <AnimatePresence>
        {isCartOpen && (
          <>
            <motion.div 
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              onClick={() => setIsCartOpen(false)}
              className="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100]" 
            />
            <motion.div 
              initial={{ x: '100%' }}
              animate={{ x: 0 }}
              exit={{ x: '100%' }}
              className="fixed right-0 top-0 bottom-0 w-full max-w-md bg-[#0a0a0a] border-l border-white/10 z-[101] flex flex-col shadow-5xl"
            >
              <div className="p-8 border-b border-white/5 flex items-center justify-between">
                <h2 className="text-2xl font-black italic uppercase tracking-tighter">Your Bag</h2>
                <button onClick={() => setIsCartOpen(false)} className="p-2 hover:bg-white/5 rounded-full transition-colors">
                  <X />
                </button>
              </div>

              <div className="flex-1 overflow-y-auto p-8">
                <AnimatePresence mode="popLayout" initial={false}>
                  {cart.length === 0 ? (
                    <motion.div 
                      key="empty"
                      initial={{ opacity: 0, scale: 0.9 }}
                      animate={{ opacity: 1, scale: 1 }}
                      exit={{ opacity: 0, scale: 0.9 }}
                      className="h-full flex flex-col items-center justify-center text-center opacity-30"
                    >
                      <ShoppingBag size={64} className="mb-6" />
                      <p className="font-black uppercase tracking-widest text-[10px]">Your bag is empty</p>
                    </motion.div>
                  ) : (
                    <div className="space-y-6">
                      {cart.map(item => (
                        <motion.div 
                          key={item.id}
                          layout
                          initial={{ opacity: 0, x: 20 }}
                          animate={{ opacity: 1, x: 0 }}
                          exit={{ opacity: 0, scale: 0.8 }}
                          className="flex gap-6 items-center bg-white/[0.02] p-4 rounded-3xl border border-white/[0.05]"
                        >
                          <div className="w-20 h-20 rounded-2xl overflow-hidden bg-[#111] shrink-0 border border-white/5 shadow-xl">
                            <img src={item.image} alt={item.name} className="w-full h-full object-cover" />
                          </div>
                          <div className="flex-1 min-w-0">
                            <h4 className="font-black text-xs uppercase tracking-tight italic text-white truncate">{item.name}</h4>
                            <p className="text-blue-500 font-black text-sm mb-3">₹{item.price}</p>
                            <div className="flex items-center gap-3">
                              <button 
                                onClick={() => updateQuantity(item.id, item.quantity - 1)}
                                className="w-7 h-7 rounded-full bg-white/5 flex items-center justify-center hover:bg-white hover:text-black transition-all"
                              >
                                <Minus size={12} />
                              </button>
                              <span className="font-black text-xs w-4 text-center">{item.quantity}</span>
                              <button 
                                onClick={() => updateQuantity(item.id, item.quantity + 1)}
                                className="w-7 h-7 rounded-full bg-white/5 flex items-center justify-center hover:bg-white hover:text-black transition-all"
                              >
                                <Plus size={12} />
                              </button>
                            </div>
                          </div>
                          <button 
                            onClick={() => removeFromCart(item.id)}
                            className="p-2 text-gray-600 hover:text-red-500 hover:bg-red-500/10 rounded-full transition-all"
                          >
                            <X size={16} />
                          </button>
                        </motion.div>
                      ))}
                    </div>
                  )}
                </AnimatePresence>
              </div>

              <div className="p-8 border-t border-white/5 bg-black/50">
                <div className="flex items-center justify-between mb-8">
                  <p className="text-gray-400 font-black text-[10px] uppercase tracking-widest">Total</p>
                  <p className="text-3xl font-black italic tracking-tighter">₹{total.toLocaleString()}</p>
                </div>
                <button 
                  disabled={cart.length === 0 || isCheckingOut}
                  onClick={handleCheckout}
                  className="w-full bg-blue-600 text-white py-5 rounded-3xl font-black text-[11px] uppercase tracking-widest shadow-2xl hover:bg-blue-700 active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:grayscale"
                >
                  {isCheckingOut ? <Loader2 className="animate-spin" /> : <>Complete Selection <ArrowRight size={18} className="stroke-[3px]" /></>}
                </button>
              </div>
            </motion.div>
          </>
        )}
      </AnimatePresence>
    </div>
  );
};

export default Store;
