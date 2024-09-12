<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pizzas = [
            [
                'name' => 'Margherita',
                'price' => 100,
                'image' => 'Margherita.jpeg',
                'description' => 'Classic delight with 100% real mozzarella cheese',
            ],
            [
                'name' => 'Veggie Supreme',
                'price' => 250,
                'image' => 'Veggie Supreme.jpeg',
                'description' => 'A supreme combination of fresh veggies and mozzarella cheese.',
            ],
            [
                'name' => 'Paneer Tikka',
                'price' => 280,
                'image' => 'Paneer Tikka.jpeg',
                'description' => 'Spicy paneer tikka with onions and peppers on a cheesy base.',
            ],
            [
                'name' => 'Farmhouse',
                'price' => 320,
                'image' => 'Farmhouse.jpeg',
                'description' => 'A blend of farm-fresh vegetables with a generous cheese topping.',
            ],
            [
                'name' => 'Mexican Green Wave',
                'price' => 350,
                'image' => 'Mexican Green Wave.jpeg',
                'description' => 'Jalapenos, bell peppers, and crunchy onions with a spicy twist.',
            ],
            [
                'name' => 'Peppy Paneer',
                'price' => 330,
                'image' => 'Peppy Paneer.jpeg',
                'description' => 'Flavorful paneer and crunchy veggies with extra mozzarella cheese.',
            ],
            [
                'name' => 'Veg Extravaganza',
                'price' => 400,
                'image' => 'Veg Extravaganza.jpeg',
                'description' => 'A loaded pizza with a variety of fresh veggies and toppings.',
            ],
            [
                'name' => 'Deluxe Veggie',
                'price' => 300,
                'image' => 'Deluxe Veggie.jpeg',
                'description' => 'A perfect combination of veggies and tangy tomato sauce.',
            ],
            [
                'name' => 'Cheese n Corn',
                'price' => 260,
                'image' => 'Cheese n Corn.jpeg',
                'description' => 'Sweet corn and melted mozzarella cheese on a delicious pizza base.',
            ],
            [
                'name' => 'Italian Delight',
                'price' => 290,
                'image' => 'Italian Delight.jpeg',
                'description' => 'A delightful combination of Italian herbs and fresh vegetables.',
            ],
            [
                'name' => 'Spicy Triple Tango',
                'price' => 350,
                'image' => 'Spicy Triple Tango.jpeg',
                'description' => 'Three types of cheese with spicy veggies for a fiery taste.',
            ],
            [
                'name' => 'Tandoori Paneer',
                'price' => 370,
                'image' => 'Tandoori Paneer.jpeg',
                'description' => 'Paneer marinated with tandoori spices on a cheesy pizza base.',
            ],
            [
                'name' => 'Country Feast',
                'price' => 310,
                'image' => 'Country Feast.jpeg',
                'description' => 'Farm-fresh vegetables with a hint of rustic flavors.',
            ]
        ];


        foreach ($pizzas as $pizza) {
            // Create each product (this is just an example; adjust according to your project logic)
            Product::create([
                'name' => $pizza['name'],
                'price' => $pizza['price'],
                'image' => $pizza['image'],
                'description' => $pizza['description'],
            ]);
        }
    }
}
