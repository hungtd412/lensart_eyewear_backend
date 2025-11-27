<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderEvent {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $eventType;
    public $metadata;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     * @param string $eventType
     * @param array $metadata
     */
    public function __construct(Order $order, string $eventType, array $metadata = []) {
        $this->order = $order;
        $this->eventType = $eventType;
        $this->metadata = $metadata;
    }

    /**
     * Convert order to array for Kafka
     *
     * @return array
     */
    public function toKafkaPayload(): array {
        $order = $this->order;
        
        return [
            'id' => $order->id,
            'user_id' => $order->user_id,
            'branch_id' => $order->branch_id,
            'date' => $order->date,
            'address' => $order->address,
            'note' => $order->note,
            'coupon_id' => $order->coupon_id,
            'total_price' => $order->total_price,
            'order_status' => $order->order_status,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'status' => $order->status,
            'user' => $order->user ? [
                'id' => $order->user->id,
                'name' => $order->user->name,
                'email' => $order->user->email,
            ] : null,
            'branch' => $order->branch ? [
                'id' => $order->branch->id,
                'name' => $order->branch->name,
                'address' => $order->branch->address,
            ] : null,
            'order_details' => $order->orderDetails->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'product_id' => $detail->product_id,
                    'product_name' => $detail->name ?? null,
                    'color' => $detail->color,
                    'quantity' => $detail->quantity,
                    'total_price' => $detail->total_price,
                ];
            })->toArray(),
            'metadata' => $this->metadata,
        ];
    }
}

