<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            [
                'name' => 'Appetizers',
                'slug' => 'appetizers',
                'items' => [
                    ['name' => 'Samosa Trio', 'price' => 5.90, 'description' => 'Crisp pastry parcels stuffed with spiced potato and peas, served with mint chutney.', 'tags' => 'Popular, Crispy, Veg', 'image' => 'assets/images/menu/appetizers.jpg'],
                    ['name' => 'Chicken Pakora', 'price' => 6.80, 'description' => 'Tender chicken strips dipped in gram-flour batter and fried until golden.', 'tags' => 'Starter, Crunchy', 'image' => 'assets/images/menu/appetizers.jpg'],
                    ['name' => 'Paneer Tikka Bites', 'price' => 7.40, 'description' => 'Charred paneer cubes marinated with yogurt, chili and kasoori methi.', 'tags' => 'Tandoor, Vegetarian', 'image' => 'assets/images/menu/grill.jpg'],
                ],
            ],
            [
                'name' => 'Grill',
                'slug' => 'grill',
                'items' => [
                    ['name' => 'Seekh Kebab', 'price' => 12.90, 'description' => 'Smoky minced lamb skewers with fresh herbs, onions and lemon.', 'tags' => 'Signature, Spicy', 'image' => 'assets/images/menu/seekh-kebab.jpg'],
                    ['name' => 'Tandoori Chicken Half', 'price' => 13.50, 'description' => 'Classic red-marinated chicken roasted in the tandoor and finished with butter.', 'tags' => 'Chargrilled, Chef Pick', 'image' => 'assets/images/menu/grill.jpg'],
                    ['name' => 'Mixed Grill Sizzler', 'price' => 18.90, 'description' => 'A hot platter of seekh kebab, chicken tikka, wings and grilled vegetables.', 'tags' => 'Sharing, House Special', 'image' => 'assets/images/menu/mix-platter.jpg'],
                ],
            ],
            [
                'name' => 'Main Course',
                'slug' => 'main-course',
                'items' => [
                    ['name' => 'Butter Chicken', 'price' => 13.80, 'description' => 'Creamy tomato-based curry with charcoal-finished chicken tikka pieces.', 'tags' => 'Best Seller, Creamy', 'image' => 'assets/images/menu/butter-chicken.jpg'],
                    ['name' => 'Lamb Rogan Josh', 'price' => 14.90, 'description' => 'Slow-cooked lamb in a deep Kashmiri gravy with aromatic whole spices.', 'tags' => 'Traditional, Rich', 'image' => 'assets/images/menu/main-course.jpg'],
                    ['name' => 'Chicken Karahi', 'price' => 13.20, 'description' => 'Fresh tomato, ginger and green chili curry served in karahi style.', 'tags' => 'Medium Hot, Fresh', 'image' => 'assets/images/menu/main-course.jpg'],
                ],
            ],
            [
                'name' => 'Veg Dishes',
                'slug' => 'veg-dishes',
                'items' => [
                    ['name' => 'Dal Makhani', 'price' => 10.40, 'description' => 'Black lentils simmered low and slow with butter, cream and warm spices.', 'tags' => 'Comfort, Vegetarian', 'image' => 'assets/images/menu/veg-dishes.jpg'],
                    ['name' => 'Saag Paneer', 'price' => 11.20, 'description' => 'Paneer cubes folded into creamy spinach gravy with garlic tempering.', 'tags' => 'Vegetarian, Classic', 'image' => 'assets/images/menu/veg-dishes.jpg'],
                    ['name' => 'Aloo Gobi Masala', 'price' => 9.80, 'description' => 'Potato and cauliflower tossed in dry masala with coriander and tomato.', 'tags' => 'Vegan Friendly, Home Style', 'image' => 'assets/images/menu/veg-dishes.jpg'],
                ],
            ],
            [
                'name' => 'Rice',
                'slug' => 'rice',
                'items' => [
                    ['name' => 'Chicken Biryani', 'price' => 12.70, 'description' => 'Layered basmati rice, saffron and spiced chicken cooked dum style.', 'tags' => 'Aromatic, Popular', 'image' => 'assets/images/menu/lamb-biryani.jpg'],
                    ['name' => 'Lamb Biryani', 'price' => 13.90, 'description' => 'Fragrant rice layered with tender lamb, mint and caramelized onions.', 'tags' => 'Signature, Aromatic', 'image' => 'assets/images/menu/lamb-biryani.jpg'],
                    ['name' => 'Jeera Rice', 'price' => 4.90, 'description' => 'Steamed basmati rice tempered with cumin seeds and clarified butter.', 'tags' => 'Side, Light', 'image' => 'assets/images/menu/rice.jpg'],
                ],
            ],
            [
                'name' => 'Seasoning',
                'slug' => 'seasoning',
                'items' => [
                    ['name' => 'Mint Yogurt Dip', 'price' => 2.20, 'description' => 'Cooling yogurt dip with mint, cumin and a touch of black salt.', 'tags' => 'Fresh, Add-on', 'image' => 'assets/images/menu/seasoning.jpg'],
                    ['name' => 'Tamarind Chutney', 'price' => 2.40, 'description' => 'Sweet and tangy tamarind glaze perfect with starters and grills.', 'tags' => 'Sweet Tangy, Add-on', 'image' => 'assets/images/menu/seasoning.jpg'],
                    ['name' => 'Smoked Chili Oil', 'price' => 2.60, 'description' => 'House-made chili oil with garlic and smoky pepper notes.', 'tags' => 'Hot, Add-on', 'image' => 'assets/images/menu/seasoning.jpg'],
                ],
            ],
            [
                'name' => 'Desserts',
                'slug' => 'desserts',
                'items' => [
                    ['name' => 'Gulab Jamun', 'price' => 5.40, 'description' => 'Warm milk dumplings soaked in cardamom and rose syrup.', 'tags' => 'Traditional, Sweet', 'image' => 'assets/images/menu/desserts.jpg'],
                    ['name' => 'Kulfi Pistachio', 'price' => 5.90, 'description' => 'Dense Indian-style frozen dessert with pistachio and saffron.', 'tags' => 'Cold, Chef Pick', 'image' => 'assets/images/menu/desserts.jpg'],
                    ['name' => 'Kheer', 'price' => 4.90, 'description' => 'Creamy rice pudding slow-cooked with milk, cardamom and nuts.', 'tags' => 'Classic, Sweet', 'image' => 'assets/images/menu/desserts.jpg'],
                ],
            ],
            [
                'name' => 'Mix Platter',
                'slug' => 'mix-platter',
                'items' => [
                    ['name' => 'Family Mix Platter', 'price' => 26.90, 'description' => 'Generous platter of kebabs, wings, paneer tikka, naan and sauces.', 'tags' => 'Sharing, Value', 'image' => 'assets/images/menu/mix-platter.jpg'],
                    ['name' => 'Signature Tandoor Platter', 'price' => 29.50, 'description' => 'Premium mixed meats, grilled vegetables and house chutneys.', 'tags' => 'Signature, Grill', 'image' => 'assets/images/menu/mix-platter.jpg'],
                    ['name' => 'Couples Grill Board', 'price' => 19.90, 'description' => 'Perfect-for-two board with chicken tikka, seekh kebab and naan.', 'tags' => 'Sharing, Popular', 'image' => 'assets/images/menu/mix-platter.jpg'],
                ],
            ],
            [
                'name' => 'Drinks',
                'slug' => 'drinks',
                'items' => [
                    ['name' => 'Mango Lassi', 'price' => 4.20, 'description' => 'Refreshing yogurt-based mango drink with a creamy finish.', 'tags' => 'Cold, Popular', 'image' => 'assets/images/menu/drinks.jpg'],
                    ['name' => 'Masala Chai', 'price' => 3.20, 'description' => 'Classic spiced tea brewed fresh with milk and aromatic spices.', 'tags' => 'Hot, Traditional', 'image' => 'assets/images/menu/drinks.jpg'],
                    ['name' => 'Lemon Mint Soda', 'price' => 3.90, 'description' => 'Sparkling soda with fresh mint, lemon and a pinch of masala salt.', 'tags' => 'Refreshing, House Drink', 'image' => 'assets/images/menu/drinks.jpg'],
                ],
            ],
        ];

        foreach ($catalog as $categoryData) {
            $category = MenuCategory::query()->updateOrCreate(
                ['slug' => $categoryData['slug']],
                ['name' => $categoryData['name']]
            );

            foreach ($categoryData['items'] as $itemData) {
                MenuItem::query()->updateOrCreate(
                    [
                        'menu_category_id' => $category->id,
                        'name' => $itemData['name'],
                    ],
                    [
                        'description' => $itemData['description'],
                        'price' => $itemData['price'],
                        'tags' => $itemData['tags'],
                        'image_path' => $itemData['image'],
                        'is_available' => true,
                    ]
                );
            }
        }
    }
}
