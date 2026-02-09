export type SubCategory = {
  name: string;
  slug: string;
};

export type MainCategory = {
  name: string;
  slug: string;
  image: string;
  imageWidth: number;
  imageHeight: number;
  subCategories: SubCategory[];
};

export const categories: MainCategory[] = [
  {
    name: "Automotive",
    slug: "automotive",
    image:
      "https://loremflickr.com/80/80",
    imageWidth: 80,
    imageHeight: 80,
    subCategories: [
      { name: "Automobiles", slug: "automobiles" },
      { name: "Automobile Interior Accessories", slug: "automobile-interior" },
      { name: "Automobile Exterior Accessories", slug: "automobile-exterior" },
      { name: "Automobile Spare Parts", slug: "automobile-spare-parts" },
      { name: "Motorcycles", slug: "motorcycles" },
      { name: "Motorcycle Accessories", slug: "motorcycle-accessories" },
      { name: "Automotive Tools", slug: "automotive-tools" },
      { name: "Automotive Care", slug: "automotive-care" },
    ],
  },

  {
    name: "Baby & Kids Fashion",
    slug: "baby-kids-fashion",
    image:
      "https://loremflickr.com/80/80",
    imageWidth: 80,
    imageHeight: 80,
    subCategories: [
      { name: "Baby Clothes", slug: "baby-clothes" },
      { name: "Boy Clothes", slug: "boy-clothes" },
      { name: "Girl Clothes", slug: "girl-clothes" },
      { name: "Boy Shoes", slug: "boy-shoes" },
      { name: "Girl Shoes", slug: "girl-shoes" },
      { name: "Kids Accessories", slug: "kids-accessories" },
      { name: "Rain Gear", slug: "rain-gear" },
    ],
  },

  {
    name: "Beauty & Care",
    slug: "beauty-care",
    image:
      "https://loremflickr.com/80/80",
    imageWidth: 80,
    imageHeight: 80,
    subCategories: [
      { name: "Skincare", slug: "skincare" },
      { name: "Makeup", slug: "makeup" },
      { name: "Hair Care", slug: "hair-care" },
      { name: "Perfumes & Fragrances", slug: "perfumes" },
      { name: "Men's Care", slug: "mens-care" },
      { name: "Beauty Tools", slug: "beauty-tools" },
    ],
  },

  {
    name: "Computer & Accessories",
    slug: "computer-accessories",
    image:
      "https://loremflickr.com/80/80",
    imageWidth: 80,
    imageHeight: 80,
    subCategories: [
      { name: "Laptops", slug: "laptops" },
      { name: "Desktop Computers", slug: "desktop-computers" },
      { name: "Monitors", slug: "monitors" },
      { name: "Keyboards & Mouse", slug: "keyboards-mouse" },
      { name: "Printers & Scanners", slug: "printers-scanners" },
      { name: "Computer Components", slug: "computer-components" },
    ],
  },

  {
    name: "Electronics",
    slug: "electronics",
    image:
      "https://loremflickr.com/80/80",
    imageWidth: 80,
    imageHeight: 80,
    subCategories: [
      { name: "TVs & Accessories", slug: "tv-accessories" },
      { name: "Home Audio & Speakers", slug: "home-audio" },
      { name: "Cameras", slug: "cameras" },
      { name: "Kitchen Appliances", slug: "kitchen-appliances" },
      { name: "Security Cameras", slug: "security-cameras" },
      { name: "Video Games", slug: "video-games" },
    ],
  },

  {
    name: "Fashion Accessories",
    slug: "fashion-accessories",
    image:
      "https://loremflickr.com/80/80",
    imageWidth: 80,
    imageHeight: 80,
    subCategories: [
      { name: "Bags", slug: "bags" },
      { name: "Watches", slug: "watches" },
      { name: "Eyewear", slug: "eyewear" },
      { name: "Jewelry", slug: "jewelry" },
      { name: "Belts", slug: "belts" },
      { name: "Hats & Caps", slug: "hats-caps" },
    ],
  },

  {
    name: "Food & Beverages",
    slug: "food-beverages",
    image:
      "https://loremflickr.com/80/80",
    imageWidth: 80,
    imageHeight: 80,
    subCategories: [
      { name: "Snacks", slug: "snacks" },
      { name: "Beverages", slug: "beverages" },
      { name: "Fresh Food", slug: "fresh-food" },
      { name: "Frozen Food", slug: "frozen-food" },
      { name: "Bakery", slug: "bakery" },
      { name: "Gift Hampers", slug: "gift-hampers" },
    ],
  },

  {
    name: "Health",
    slug: "health",
    image:
      "https://loremflickr.com/80/80",
    imageWidth: 80,
    imageHeight: 80,
    subCategories: [
      { name: "Medicine", slug: "medicine" },
      { name: "Food Supplements", slug: "supplements" },
      { name: "Medical Supplies", slug: "medical-supplies" },
      { name: "Health Monitors", slug: "health-monitors" },
      { name: "First Aid", slug: "first-aid" },
    ],
  },

  {
    name: "Home & Living",
    slug: "home-living",
    image:
      "https://loremflickr.com/80/80",
    imageWidth: 80,
    imageHeight: 80,
    subCategories: [
      { name: "Furniture", slug: "furniture" },
      { name: "Home Decor", slug: "home-decor" },
      { name: "Kitchenware", slug: "kitchenware" },
      { name: "Bedding", slug: "bedding" },
      { name: "Gardening", slug: "gardening" },
      { name: "Home Improvement", slug: "home-improvement" },
    ],
  },
];
