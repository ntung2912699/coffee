<?php
namespace App\Repositories\Order;

use App\Repositories\BaseRepository;
use App\Models\Order_item;

class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Order_item::class;
    }

    public function deleteByOrderId($orderId)
    {
        try {
            // Xóa tất cả các OrderItem liên quan đến OrderId
            $orderItems = Order_item::where('order_id', $orderId)->get();

            foreach ($orderItems as $orderItem) {
                $orderItem->delete();
            }

            return true;
        } catch (\Exception $exception) {
            throw new \Exception("Lỗi khi xóa order items: " . $exception->getMessage());
        }
    }
}
