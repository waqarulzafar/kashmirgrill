<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            [
                'name' => 'Antipasti',
                'slug' => 'antipasti',
                'image' => '/images/menu/category/antipasti.jpg',
                'items' => [
                    ['name' => 'Samosa Chaat', 'price' => 4.50, 'description' => 'Samosa with chickpeas, yogurt, tamarind, and house spices.', 'image' => '/images/menu/category/antipasti/SAMOSA CHAAT.jpg'],
                    ['name' => 'Onion Rings', 'price' => 3.00, 'description' => 'Crispy onion rings fried until golden.', 'image' => '/images/menu/category/antipasti/ONION RINGS GPZ.jpg'],
                    ['name' => 'French Fries', 'price' => 3.50, 'description' => 'Classic crispy fries.', 'image' => '/images/menu/category/antipasti/FRENCH FRIES.jpg'],
                    ['name' => 'Chicken Soup', 'price' => 6.00, 'description' => 'Chicken soup with mixed vegetables and herbs.', 'image' => '/images/menu/category/antipasti/CHICKEN SOUP.jpg'],
                    ['name' => 'Pakora Mix', 'price' => 3.50, 'description' => 'Mixed vegetable pakora with gram flour and spices.', 'image' => '/images/menu/category/antipasti/PAKORA MIX 6 PZ.jpg'],
                    ['name' => 'Paneer Pakora', 'price' => 4.00, 'description' => 'Paneer pakora with gram flour and spices.', 'image' => '/images/menu/category/antipasti/PANEER PAKORA GPZ.jpg'],
                    ['name' => 'Chicken Pakora', 'price' => 6.00, 'description' => 'Chicken pakora in seasoned gram flour batter.', 'image' => '/images/menu/category/antipasti/CHICKEN PAKORA GPZ.jpg'],
                    ['name' => 'Gamberi Pakora', 'price' => 6.00, 'description' => 'Shrimp pakora fried with house spices.', 'image' => '/images/menu/category/antipasti/GAMBERI PAKORA GPZ.jpg'],
                    ['name' => 'Samosa Verdure (2 pcs)', 'price' => 3.00, 'description' => 'Vegetable samosa with potato and peas.', 'image' => '/images/menu/category/antipasti/SAMOSA VERDURE 2PZ.jpg'],
                    ['name' => 'Vegetable Soup', 'price' => 4.00, 'description' => 'Mixed vegetable soup with aromatic herbs.', 'image' => '/images/menu/category/antipasti/VEGETABLE SOUP_.jpg'],
                    ['name' => 'Samosa Carne (2 pcs)', 'price' => 4.00, 'description' => 'Meat samosa with spices and herbs.', 'image' => '/images/menu/category/antipasti/SAMOSA CARNE 2PZ.jpg'],
                    ['name' => 'Lentil Soup', 'price' => 4.50, 'description' => 'Lentil soup with garlic, ginger, and warm spices.', 'image' => '/images/menu/category/antipasti/LENTIL SOUP.jpg'],
                ],
            ],
            [
                'name' => 'Griglia',
                'slug' => 'griglia',
                'image' => '/images/menu/category/griglia.jpg',
                'items' => [
                    ['name' => 'Chicken Tandoori', 'price' => 10.00, 'description' => 'Tandoori chicken marinated with yogurt and spices.', 'image' => '/images/menu/category/griglia/CHICKEN TANDOORI.jpg'],
                    ['name' => 'Grill Chicken', 'price' => 16.00, 'description' => 'Marinated grilled chicken with lemon and spices.', 'image' => '/images/menu/category/griglia/GRILL CHICKEN.jpg'],
                    ['name' => 'Mix Grill Tandoori', 'price' => 13.00, 'description' => 'Assorted tandoori grilled meats.', 'image' => '/images/menu/category/griglia/MIX GRILL TANDOORI.jpg'],
                    ['name' => 'Chicken Tikka', 'price' => 10.00, 'description' => 'Boneless chicken tikka with tandoori spices.', 'image' => '/images/menu/category/griglia/CHICKEN TIKKA.jpg'],
                    ['name' => 'Lamb Chops Grill (5 pcs)', 'price' => 15.00, 'description' => 'Grilled lamb chops with herbs and spices.', 'image' => '/images/menu/category/griglia/LAMB CHAMP GRILL.jpg'],
                    ['name' => 'Fish Grill', 'price' => 13.00, 'description' => 'Grilled fish with lemon, garlic, and spices.', 'image' => '/images/menu/category/griglia/FISH GRILL.jpg'],
                    ['name' => 'Malai Tikka', 'price' => 10.00, 'description' => 'Creamy chicken tikka with yogurt and butter.', 'image' => '/images/menu/category/griglia/MALAI TIKKA.jpg'],
                    ['name' => 'Beef Chapli Kebab', 'price' => 9.00, 'description' => 'Minced beef chapli kebab with fresh herbs.', 'image' => '/images/menu/category/griglia/BEEF CHAPLI KEBAB.jpg'],
                    ['name' => 'Beef Seekh Kebab (5 pcs)', 'price' => 9.00, 'description' => 'Beef seekh kebab with green chili and coriander.', 'image' => '/images/menu/category/griglia/BEEF SEEKH KEBAB.jpg'],
                ],
            ],
            [
                'name' => 'Primi Piatti',
                'slug' => 'primi-piatti',
                'image' => '/images/menu/category/primi-piatti.jpg',
                'items' => [
                    ['name' => 'Shinwari Lamb Karahi', 'price' => 11.00, 'description' => 'Shinwari-style lamb karahi with tomato, garlic, and ginger.', 'image' => '/images/menu/category/primi-piatti/SHINWARI LAMB KARAHI.jpg'],
                    ['name' => 'Karela Gosht', 'price' => 10.00, 'description' => 'Meat cooked with bitter gourd and spices.', 'image' => '/images/menu/category/primi-piatti/KARELA GOSHT.jpg'],
                    ['name' => 'Shinwari Chicken Karahi', 'price' => 9.50, 'description' => 'Shinwari chicken karahi with fresh tomato and chili.', 'image' => '/images/menu/category/primi-piatti/SHINWARI CHICKEN KARAHI.jpg'],
                    ['name' => 'Daal Gosht', 'price' => 9.50, 'description' => 'Lentils and beef cooked with aromatic spices.', 'image' => '/images/menu/category/primi-piatti/DAAL GOSHT.jpg'],
                    ['name' => 'Shinwari Beef Karahi', 'price' => 9.50, 'description' => 'Shinwari beef karahi with coriander and chili.', 'image' => '/images/menu/category/primi-piatti/SHINWARI BEEF KARAHI.jpg'],
                    ['name' => 'Palak Gosht', 'price' => 9.00, 'description' => 'Spinach and beef curry with house spices.', 'image' => '/images/menu/category/primi-piatti/PALAK GOSHT.jpg'],
                    ['name' => 'Chicken Boneless Handi', 'price' => 11.00, 'description' => 'Boneless chicken handi with tomato, butter, and spices.', 'image' => '/images/menu/category/primi-piatti/CHICKEN BONELESS HANDI.jpg'],
                    ['name' => 'Butter Chicken', 'price' => 10.00, 'description' => 'Creamy butter chicken with tomato and aromatic spices.', 'image' => '/images/menu/category/primi-piatti/BUTTER CHICKEN.jpg'],
                    ['name' => 'Chicken Jalfrezi', 'price' => 10.00, 'description' => 'Chicken jalfrezi with peppers, onion, and tomato.', 'image' => '/images/menu/category/primi-piatti/CHICKEN JALFREZI.jpg'],
                    ['name' => 'Chicken Tikka Masala', 'price' => 10.00, 'description' => 'Chicken tikka masala in a rich spiced sauce.', 'image' => '/images/menu/category/primi-piatti/CHICKEN TIKKA MASALA.jpg'],
                    ['name' => 'Bhindi Gosht', 'price' => 10.00, 'description' => 'Okra and meat curry with house masala.', 'image' => '/images/menu/category/primi-piatti/BHINDI GOSHT.jpg'],
                    ['name' => 'Aloo Keema', 'price' => 9.00, 'description' => 'Minced meat with potatoes and warming spices.', 'image' => '/images/menu/category/primi-piatti/ALOO KEEMA.jpg'],
                    ['name' => 'Chicken Palak', 'price' => 7.00, 'description' => 'Chicken with spinach, tomato, and ginger.', 'image' => '/images/menu/category/primi-piatti/CHICKEN PALAK.jpg'],
                    ['name' => 'Chicken Korma', 'price' => 9.00, 'description' => 'Classic chicken korma with yogurt and mild spices.', 'image' => '/images/menu/category/primi-piatti/CHICKEN KORMA.jpg'],
                    ['name' => 'Mutton Korma', 'price' => 10.00, 'description' => 'Mutton korma with yogurt and aromatic spices.', 'image' => '/images/menu/category/primi-piatti/MUTTON KORMA.jpg'],
                    ['name' => 'Beef Korma', 'price' => 9.50, 'description' => 'Beef korma cooked in a rich spiced gravy.', 'image' => '/images/menu/category/primi-piatti/BEEF KORMA.jpg'],
                    ['name' => 'Fish Curry', 'price' => 11.00, 'description' => 'Fish curry with tomato, garlic, and turmeric.', 'image' => '/images/menu/category/primi-piatti/FISH CURRY.jpg'],
                    ['name' => 'Gamberi Curry', 'price' => 10.00, 'description' => 'Shrimp curry with tomato, garlic, and spices.', 'image' => '/images/menu/category/primi-piatti/GAMBERI CURRY.jpg'],
                ],
            ],
            [
                'name' => 'Veg Dishes',
                'slug' => 'veg-dishes',
                'image' => '/images/menu/category/veg-dishes.jpg',
                'items' => [
                    ['name' => 'Palak Paneer', 'price' => 8.00, 'description' => 'Paneer cooked with spinach and spices.', 'image' => '/images/menu/category/veg-dishes/PALAK PANEER.jpg'],
                    ['name' => 'Bhindi Okra', 'price' => 8.00, 'description' => 'Okra stir-cooked with onion, tomato, and masala.', 'image' => '/images/menu/category/veg-dishes/BHINDI OKRA.jpg'],
                    ['name' => 'Chana Masala', 'price' => 7.00, 'description' => 'Chickpea curry with tomato and aromatic spices.', 'image' => '/images/menu/category/veg-dishes/CHANA MASALA.jpg'],
                    ['name' => 'Karela (Zucca Amara)', 'price' => 8.00, 'description' => 'Bitter gourd cooked with traditional spices.', 'image' => '/images/menu/category/veg-dishes/KARELA ZUCCA AMARA.jpg'],
                    ['name' => 'Malai Kofta', 'price' => 7.00, 'description' => 'Soft kofta in creamy spiced gravy.', 'image' => '/images/menu/category/veg-dishes/MALAI KOFTA.jpg'],
                    ['name' => 'Dal Makhni', 'price' => 7.00, 'description' => 'Slow-cooked black lentils with butter and cream.', 'image' => '/images/menu/category/veg-dishes/DAL MAKHNI.jpg'],
                    ['name' => 'Mix Daal', 'price' => 7.00, 'description' => 'Mixed lentils tempered with spices.', 'image' => '/images/menu/category/veg-dishes/MIX DAAL.jpg'],
                    ['name' => 'Dal Mash', 'price' => 7.00, 'description' => 'Urad dal mash with ginger and spices.', 'image' => '/images/menu/category/veg-dishes/DAL MASH.jpg'],
                    ['name' => 'Mix Vegetables', 'price' => 7.00, 'description' => 'Seasonal mixed vegetables with mild spices.', 'image' => '/images/menu/category/veg-dishes/MIX VEGETABLES.jpg'],
                    ['name' => 'Shahi Paneer', 'price' => 7.00, 'description' => 'Paneer in rich, creamy tomato-based gravy.', 'image' => '/images/menu/category/veg-dishes/SHAHI PANEER.jpg'],
                ],
            ],
            [
                'name' => 'Rice',
                'slug' => 'rice',
                'image' => '/images/menu/category/rice.jpg',
                'items' => [
                    ['name' => 'Veg Biryani', 'price' => 6.00, 'description' => 'Basmati rice with mixed vegetables and spices.', 'image' => '/images/menu/category/rice/VEG BIRYANI.jpg'],
                    ['name' => 'Gamberi Biryani', 'price' => 9.50, 'description' => 'Shrimp biryani with basmati rice and spices.', 'image' => '/images/menu/category/rice/GAMBERI BIRYANI.jpg'],
                    ['name' => 'Plain White Rice', 'price' => 5.00, 'description' => 'Steamed plain white rice.', 'image' => '/images/menu/category/rice/PLAIN WHITE RICE.jpg'],
                    ['name' => 'Chicken Biryani', 'price' => 7.00, 'description' => 'Chicken biryani with aromatic basmati rice.', 'image' => '/images/menu/category/rice/CHICKEN BIRYANI.jpg'],
                    ['name' => 'Dum Biryani', 'price' => 8.00, 'description' => 'Dum-cooked biryani with tandoori chicken and spices.', 'image' => '/images/menu/category/rice/DUM BIRYANI.jpg'],
                    ['name' => 'Mutton Biryani', 'price' => 9.00, 'description' => 'Mutton biryani with layered basmati rice.', 'image' => '/images/menu/category/rice/MUTTON BIRYANI.jpg'],
                    ['name' => 'Beef Biryani', 'price' => 8.50, 'description' => 'Beef biryani with basmati rice and spices.', 'image' => '/images/menu/category/rice/BEEF BIRYANI.jpg'],
                ],
            ],
            [
                'name' => 'Seasoning',
                'slug' => 'seasoning',
                'image' => '/images/menu/category/seasoning.jpg',
                'items' => [
                    ['name' => 'Naan', 'price' => 1.50, 'description' => 'Traditional tandoor naan bread.', 'image' => '/images/menu/category/seasoning/NAAN.jpg'],
                    ['name' => 'Butter Naan', 'price' => 2.50, 'description' => 'Soft naan finished with butter.', 'image' => '/images/menu/category/seasoning/BUTTER NAAN.jpg'],
                    ['name' => 'Roti', 'price' => 1.00, 'description' => 'Classic whole wheat roti.', 'image' => '/images/menu/category/seasoning/ROTI.jpg'],
                    ['name' => 'Garlic Naan', 'price' => 2.00, 'description' => 'Naan bread with fresh garlic.', 'image' => '/images/menu/category/seasoning/GARLIC NAAN.jpg'],
                    ['name' => 'Aloo Naan', 'price' => 2.50, 'description' => 'Stuffed naan with spiced potato filling.', 'image' => '/images/menu/category/seasoning/ALU NAAN.jpg'],
                    ['name' => 'Keema Naan', 'price' => 3.00, 'description' => 'Stuffed naan with seasoned minced meat.', 'image' => '/images/menu/category/seasoning/KEEMA NAAN.jpg'],
                    ['name' => 'Cheese Naan', 'price' => 3.00, 'description' => 'Naan filled with melted cheese.', 'image' => '/images/menu/category/seasoning/CHEESE NAAN.jpg'],
                    ['name' => 'Raita Salata', 'price' => 3.00, 'description' => 'Yogurt raita with cucumber, onion, and tomato.', 'image' => '/images/menu/category/seasoning/RAITA SALATA.jpg'],
                    ['name' => 'Insalata Mista', 'price' => 4.00, 'description' => 'Mixed salad with olive oil dressing.', 'image' => '/images/menu/category/seasoning/INSALATA MISTA.jpg'],
                    ['name' => 'Insalata con Cipolla', 'price' => 5.00, 'description' => 'Fresh salad with onion and tomato.', 'image' => '/images/menu/category/seasoning/INSALATA CON CIPOLLA.jpg'],
                    ['name' => 'Insalata Speciale', 'price' => 6.00, 'description' => 'Special salad with tuna, mozzarella, and vegetables.', 'image' => '/images/menu/category/seasoning/INSALATA SPECIALE.jpg'],
                ],
            ],
            [
                'name' => 'Desserts',
                'slug' => 'desserts',
                'image' => '/images/menu/category/desserts.jpg',
                'items' => [
                    ['name' => 'Gulab Jamun (2 pcs)', 'price' => 3.00, 'description' => 'Soft milk dumplings in saffron sugar syrup.', 'image' => '/images/menu/category/desserts/GULAB JAMUN.jpg'],
                    ['name' => 'Shahi Kheer', 'price' => 3.00, 'description' => 'Traditional rice pudding with nuts and saffron.', 'image' => '/images/menu/category/desserts/SHAHI KHEER.jpg'],
                    ['name' => 'Jalebi (1 pc)', 'price' => 1.00, 'description' => 'Crispy spiral sweet soaked in sugar syrup.', 'image' => '/images/menu/category/desserts/JALEBI.jpg'],
                    ['name' => 'Gajar Halwa', 'price' => 3.00, 'description' => 'Carrot halwa with milk, nuts, and cardamom.', 'image' => '/images/menu/category/desserts/GAJAR HALWA.jpg'],
                    ['name' => 'Barfi (1 pc)', 'price' => 1.00, 'description' => 'Classic milk sweet with cardamom and nuts.', 'image' => '/images/menu/category/desserts/BARFI.jpg'],
                    ['name' => 'Patisa (1 pc)', 'price' => 1.00, 'description' => 'Flaky gram-flour sweet with cardamom and nuts.', 'image' => '/images/menu/category/desserts/PATISA.jpg'],
                ],
            ],
            [
                'name' => 'Mix Platter',
                'slug' => 'mix-platter',
                'image' => '/images/menu/category/mix-platter.jpg',
                'items' => [
                    ['name' => 'Fish Tawa', 'price' => 30.00, 'description' => 'Orata fish, shrimp, chicken tikka, chicken tandoori, and rice platter.', 'image' => '/images/menu/category/mix-platter/FISH TAWA.jpg'],
                    ['name' => 'Mix Grill Tawa Special (Per 2)', 'price' => 30.00, 'description' => 'Mixed grill platter for 2 with naan, salad, and rice.', 'image' => '/images/menu/category/mix-platter/MIX GRILL TAWA SPECIAL PER 2.jpg'],
                    ['name' => 'Mix Grill Tawa Special (Per 4)', 'price' => 60.00, 'description' => 'Large mixed grill platter for 4 with naan, salad, and rice.', 'image' => '/images/menu/category/mix-platter/MIX GRILL TAWA SPECIAL PER 4.jpg'],
                    ['name' => 'Grill Chicken & Rice (Per Person)', 'price' => 32.00, 'description' => 'Full grill chicken tandoori with rice, potatoes, and mixed salad.', 'image' => '/images/menu/category/mix-platter/GRILL CHICKEN & RICE PER 2.jpg'],
                ],
            ],
            [
                'name' => 'Drinks',
                'slug' => 'drinks',
                'image' => '/images/menu/category/drinks.jpg',
                'items' => [
                    ['name' => 'Acqua Naturale/Frizzante (0.5L)', 'price' => 1.50, 'description' => 'Still or sparkling water (0.5L).', 'image' => '/images/menu/category/drinks/ACQUA NATURALE FRIZZANTE 0.5L.jpg'],
                    ['name' => 'Acqua Naturale/Frizzante (1L)', 'price' => 2.50, 'description' => 'Still or sparkling water (1L).', 'image' => '/images/menu/category/drinks/ACQUA NATURALE FRIZZANTE 1L.jpg'],
                    ['name' => 'Lattine (33cl)', 'price' => 2.00, 'description' => 'Soft drinks can (33cl).', 'image' => '/images/menu/category/drinks/LATTINE (33CL].jpg'],
                    ['name' => 'Fresh Juice', 'price' => 3.00, 'description' => 'Fresh juice from seasonal fruits and vegetables.', 'image' => '/images/menu/category/drinks/FRESH JUICE.jpg'],
                    ['name' => 'Chai Pakistani', 'price' => 2.00, 'description' => 'Pakistani black tea with milk and sugar.', 'image' => '/images/menu/category/drinks/CHAI PAKISTANI.jpg'],
                    ['name' => 'Lassi Mango', 'price' => 4.00, 'description' => 'Sweet mango yogurt drink.', 'image' => '/images/menu/category/drinks/LASSI MANGO.jpg'],
                    ['name' => 'Lassi Salato o Dolce', 'price' => 3.00, 'description' => 'Salted or sweet traditional lassi.', 'image' => '/images/menu/category/drinks/LASSI SALATO O.jpg'],
                    ['name' => 'Nimbu Shikanji', 'price' => 2.50, 'description' => 'Lemon mint cooler with sugar and salt.', 'image' => '/images/menu/category/drinks/NIMBU SHIKANJI.jpg'],
                    ['name' => 'Caffe', 'price' => 1.50, 'description' => 'Espresso or macchiato.', 'image' => '/images/menu/category/drinks/CAFFE.jpg'],
                    ['name' => 'Tea con Cardamomo', 'price' => 2.00, 'description' => 'Cardamom tea with spices and milk.', 'image' => '/images/menu/category/drinks/TEA CON CARDAMOMO.jpg'],
                    ['name' => 'Mint Margarita', 'price' => 3.50, 'description' => 'Mint lemon cooler with crushed ice.', 'image' => '/images/menu/category/drinks/MINT MARGARITA.jpg'],
                ],
            ],
        ];

        DB::transaction(function () use ($catalog): void {
            MenuItem::query()->delete();
            MenuCategory::query()->delete();

            foreach ($catalog as $categoryData) {
                $category = MenuCategory::query()->create([
                    'name' => $categoryData['name'],
                    'slug' => $categoryData['slug'],
                ]);

                foreach ($categoryData['items'] as $itemData) {
                    MenuItem::query()->create([
                        'menu_category_id' => $category->id,
                        'name' => $itemData['name'],
                        'description' => $itemData['description'],
                        'price' => $itemData['price'],
                        'tags' => null,
                        'image_path' => $itemData['image'] ?? $categoryData['image'],
                        'is_available' => true,
                    ]);
                }
            }
        });
    }
}
