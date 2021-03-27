<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => rand(0, 1) === 1 ? 'finish' : 'pending',
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $this->createDetailOrders($order);

            $this->createTransaction($order);
        });
    }

    private function createDetailOrders(Order $order)
    {
        $productsId = Product::select('id')->limit(rand(1, 5))->inRandomOrder()->get()->pluck('id');
        
        $productsId->each(function ($productId) use ($order) {
            $order->detail_orders()->attach(
                $productId,
                [
                'qty' => rand(1, 5),
                'message' => $this->getDetailOrderMessage()]
            );
        });
    }

    private function createTransaction(Order $order)
    {
        $orderProducts = $order->detail_orders;
        $total = 0;

        foreach ($orderProducts as $orderProduct) {
            $total += $orderProduct->price * $orderProduct->pivot->qty;
        }

        $order->transaction()->create([
            'total' => $total,
        ]);
    }

    private function getDetailOrderMessage()
    {
        $text = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s";
        $text_split = explode(' ', $text);
        $max = rand(5, 25);

        $output = [];
        for ($i=0; $i < $max; $i++) {
            $output[] = $text_split[$i];
        }

        return join(' ', $output);
    }
}
