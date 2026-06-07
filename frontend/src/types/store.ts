import { Timestamp } from 'firebase/firestore';

export interface Product {
  id: string;
  name: string;
  description: string;
  price: number;
  image: string;
  category: string;
  stock: number;
  createdAt: Timestamp | Date;
}

export interface CartItem extends Product {
  quantity: number;
}

export type OrderStatus = 'pending' | 'paid' | 'shipped' | 'cancelled';

export interface Order {
  id: string;
  customerEmail: string;
  customerName: string;
  items: CartItem[];
  totalAmount: number;
  status: OrderStatus;
  razorpayOrderId?: string;
  razorpayPaymentId?: string;
  createdAt: Timestamp | Date;
}
