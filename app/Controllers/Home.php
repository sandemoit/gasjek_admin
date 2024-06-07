<?php

namespace App\Controllers;

use App\Models\BannerModel;
use App\Models\DriverModel;
use Myth\Auth\Models\UserModel;
use App\Models\UserModelApi;
use App\Models\RestaurantModel;
use App\Models\FoodModel;
use App\Models\DistanceModel;
use App\Models\FeatureModel;
use App\Models\WalletModel;
use App\Models\ApplicationModel;
use App\Models\ReviewModel;
use App\Models\MitraModel;
use Myth\Auth\Password;
use CodeIgniter\Config\Services;
use CodeIgniter\Pager\Pager;

class Home extends BaseController
{
    protected $bannerModel;
    protected $userModel;
    protected $driverModel;
    protected $restaurantModel;
    protected $foodModel;
    protected $distanceModel;
    protected $featureModel;
    protected $userModelApi;
    protected $walletModel;
    protected $applicationModel;
    protected $reviewModel;
    protected $mitraModel;

    public function __construct()
    {
        session();
        $this->bannerModel = new bannerModel();
        $this->userModel = new userModel();
        $this->driverModel = new driverModel();
        $this->restaurantModel = new restaurantModel();
        $this->foodModel = new foodModel();
        $this->distanceModel = new distanceModel();
        $this->featureModel = new featureModel();
        $this->userModelApi = new userModelApi();
        $this->walletModel = new walletModel();
        $this->applicationModel = new applicationModel();
        $this->reviewModel = new reviewModel();
        $this->mitraModel = new mitraModel();
    }

    public function index()
    {
        // Cek apakah terdapat permintaan POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Kirim notifikasi dan dapatkan status pengiriman
            $fcm_token = '/topics/Driver';
            $title = 'Pesanan baru';
            $body = 'Pesanan Membutuhkan Driver';
            $notificationStatus = $this->sendNotification($fcm_token, $title, $body);

            // Jika pengiriman notifikasi berhasil, atur flashdata
            if ($notificationStatus === true) {
                session()->setFlashdata('success', 'Notifikasi berhasil dikirim');
            }
        }

        $db = \Config\Database::connect();

        $query_banner = $db->query("SELECT * FROM tb_banner");
        $query_user = $db->query("SELECT * FROM tb_pengguna");
        $query_driver = $db->query("SELECT * FROM tb_driver");
        $query_restaurant = $db->query("SELECT * FROM tb_restaurant");

        $banners = $query_banner->getNumRows();
        $users = $query_user->getNumRows();
        $restaurants = $query_restaurant->getNumRows();
        $drivers = $query_driver->getNumRows();

        $data = [
            'title' => '',
            'drivers' => $drivers,
            'users' => $users,
            'restaurants' => $restaurants,
            'banners' => $banners

        ];

        return view('pages/home', $data);
    }

    public function login()
    {

        return view('pages/login');
    }

    public function banner()
    {
        // dd($this->request->getIPAddress());
        $current_page = $this->request->getVar('page_culture') ? $this->request->getVar('page_culture') : 1;
        $banners = $this->bannerModel;
        $data = [
            'title' => 'Banner',
            'banners' => $banners->paginate(4, 'banners'),
            'pager' => $this->bannerModel->pager,
            'current_page' => $current_page,
            'validation' => \Config\Services::validation()

        ];
        return view('pages/banner', $data);
    }

    public function mitra()
    {
        // Ambil data mitra dengan restoran yang sudah di-join
        $currentPage = $this->request->getVar('page_mitra') ? $this->request->getVar('page_mitra') : 1;
        $mitra = $this->mitraModel->getRestoWithMitra();
        $mitras = $this->mitraModel->orderBy('id_mitra', 'DESC');

        // Variabel untuk data yang akan dipass ke view
        $data = [
            'title' => 'Mitra',
            'mitra' => $mitra,
            'mitras' => $mitras->paginate(10, 'mitras'),
            'pager' => $this->mitraModel->pager,
            'current_page' => $currentPage,
        ];

        return view('pages/mitra', $data);
    }

    public function mitra_delete($id_mitra)
    {
        if ($id_mitra != null) {
            // Ambil data mitra berdasarkan id_mitra
            $mitra = $this->mitraModel->find($id_mitra);
            if ($mitra) {
                // Update status mitra menjadi 'success'
                $update_mitra = $this->mitraModel->update($id_mitra, ['status' => 'deleted']);

                // Jika update pada tb_mitra berhasil, lakukan update pada tb_restaurant
                if ($update_mitra) {
                    // Ambil id_restaurant berdasarkan email mitra
                    $restaurant = $this->restaurantModel->where('user_email_mitra', $mitra['user_email_mitra'])->first();

                    if ($restaurant) {
                        // Perbarui is_active di tb_restaurant menjadi true
                        $update_restaurant = $this->restaurantModel->update($restaurant['id_restaurant'], ['is_active' => 'false', 'is_open' => 'false']);

                        if ($update_restaurant) {
                            // Pesan sukses jika kedua update berhasil
                            session()->setFlashdata('message', 'Mitra berhasil dinonaktifkan');
                        } else {
                            // Pesan kesalahan jika update tb_restaurant gagal
                            session()->setFlashdata('error', 'Gagal memperbarui status restaurant');
                        }
                    } else {
                        // Jika tidak ditemukan restaurant, berikan pesan kesalahan
                        session()->setFlashdata('error', 'Restaurant tidak ditemukan');
                    }
                } else {
                    // Pesan kesalahan jika update tb_mitra gagal
                    session()->setFlashdata('error', 'Gagal memperbarui status mitra');
                }
            } else {
                // Jika tidak ditemukan mitra, berikan pesan kesalahan
                session()->setFlashdata('error', 'Mitra tidak ditemukan');
            }

            // Redirect ke halaman mitra
            return redirect()->to(base_url() . '/mitra');
        } else {
            // Jika $id_mitra null, berikan pesan kesalahan
            session()->setFlashdata('error', 'ID Mitra tidak valid');
            return redirect()->to(base_url() . '/mitra');
        }
    }

    public function mitra_cancel($id_mitra)
    {
        if ($id_mitra != null) {
            // Ambil data mitra berdasarkan id_mitra
            $mitra = $this->mitraModel->find($id_mitra);
            if ($mitra) {
                // Update status mitra menjadi 'success'
                $update_mitra = $this->mitraModel->update($id_mitra, ['status' => 'cancel']);

                // Jika update pada tb_mitra berhasil, lakukan update pada tb_restaurant
                if ($update_mitra) {
                    // Ambil id_restaurant berdasarkan email mitra
                    $restaurant = $this->restaurantModel->where('user_email_mitra', $mitra['user_email_mitra'])->first();

                    if ($restaurant) {
                        // Perbarui is_active di tb_restaurant menjadi true
                        $update_restaurant = $this->restaurantModel->update($restaurant['id_restaurant'], ['is_active' => 'false', 'is_open' => 'false']);

                        if ($update_restaurant) {
                            // Pesan sukses jika kedua update berhasil
                            session()->setFlashdata('message', 'Mitra berhasil dicancel');
                        } else {
                            // Pesan kesalahan jika update tb_restaurant gagal
                            session()->setFlashdata('error', 'Gagal memperbarui status restaurant');
                        }
                    } else {
                        // Jika tidak ditemukan restaurant, berikan pesan kesalahan
                        session()->setFlashdata('error', 'Restaurant tidak ditemukan');
                    }
                } else {
                    // Pesan kesalahan jika update tb_mitra gagal
                    session()->setFlashdata('error', 'Gagal memperbarui status mitra');
                }
            } else {
                // Jika tidak ditemukan mitra, berikan pesan kesalahan
                session()->setFlashdata('error', 'Mitra tidak ditemukan');
            }

            // Redirect ke halaman mitra
            return redirect()->to(base_url() . '/mitra');
        } else {
            // Jika $id_mitra null, berikan pesan kesalahan
            session()->setFlashdata('error', 'ID Mitra tidak valid');
            return redirect()->to(base_url() . '/mitra');
        }
    }

    public function wallet()
    {
        $current_page = $this->request->getVar('page_wallet') ? $this->request->getVar('page_wallet') : 1;
        $wallets = $this->walletModel;

        $data = [
            'title' => 'Transaksi Saldo',
            'wallets' => $wallets->paginate(10, 'wallet'),
            'pager' => $this->walletModel->pager,
            'current_page' => $current_page,
            'validation' => \Config\Services::validation()
        ];
        return view('pages/wallet', $data);
    }

    public function add_wallet()
    {
        $email = $this->request->getPost('email');
        $nominal = $this->request->getPost('nominal');
        $user = $this->userModelApi->where('email_pengguna', $email)->first();
        $user = $this->driverModel->where('email_rider', $email)->first();

        $data = [
            'id_transaction' => rand(1000, 9999),
            'method_payment' => 'top_up',
            'status_payment' => 'success',
            'user_name' => $user['username_rider'] ? $user['username_rider'] : $user['nama_pengguna'],
            'balance' => $nominal,
            'type_payment' => 'admin',
            'date' => date('Y-m-d'),
            'id_user' => $user['id_driver'] ? $user['id_driver'] : $user['id_user'],
            'role' => $user['id_driver'] ? 'driver' : 'user'
        ];

        if ($this->walletModel->insert($data)) {
            return redirect()->to(base_url('wallet'))->with('message', 'Wallet added successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add wallet');
        }
    }

    public function check_email()
    {
        $email = $this->request->getPost('email');

        $user = $this->userModelApi->where('email_pengguna', $email)->first();
        $driver = $this->driverModel->where('email_rider', $email)->first();

        if ($user || $driver) {
            return $this->response->setJSON(['exists' => true]);
        } else {
            return $this->response->setJSON(['exists' => false]);
        }
    }

    public function user()
    {

        $current_page = $this->request->getVar('page_user') ? $this->request->getVar('page_user') : 1;
        $users = $this->userModelApi;
        $data = [
            'title' => 'Pengguna',
            'users' => $users->paginate(9, 'users'),
            'pager' => $this->userModelApi->pager,
            'current_page' => $current_page

        ];
        return view('pages/user', $data);
    }

    public function driver()
    {
        $current_page = $this->request->getVar('page_culture') ? $this->request->getVar('page_culture') : 1;
        $drivers = $this->driverModel;
        $data = [
            'title' => 'Driver',
            'drivers' => $drivers->paginate(10, 'drivers'),
            'pager' => $this->driverModel->pager,
            'current_page' => $current_page
        ];
        return view('pages/driver', $data);
    }

    public function order()
    {

        $data = [
            'title' => 'Pesanan',
        ];
        return view('pages/order', $data);
    }

    public function restaurant()
    {
        $current_page = $this->request->getVar('page_culture') ? $this->request->getVar('page_culture') : 1;

        // Ambil hanya restoran yang is_active-nya true
        $restaurants = $this->restaurantModel->where('is_active', 'true');

        $data = [
            'title' => 'Restoran',
            'restaurants' => $restaurants->paginate(10, 'restaurants'),
            'pager' => $restaurants->pager, // Perhatikan penggunaan pager dari $restaurants
            'current_page' => $current_page,
            'validation' => \Config\Services::validation()
        ];
        return view('pages/restaurant', $data);
    }

    public function edit_restaurant($id_restaurant)
    {
        $restaurants = $this->restaurantModel->getRestaurant($id_restaurant);

        $data = [
            'title' => 'Ubah Restoran',
            'restaurants' => $restaurants,
            'validation' => \Config\Services::validation()

        ];
        return view('pages/edit_restaurant', $data);
    }

    public function edit_food($id_food)
    {


        $foods = $this->foodModel->getFoodId($id_food);
        $current_page = $this->request->getVar('page_culture') ? $this->request->getVar('page_culture') : 1;
        $restaurants = $this->restaurantModel->getRestaurant($foods['id_restaurant']);


        // dd() ;

        $data = [
            'title' => 'Ubah Makanan',
            'foods' => $foods,
            'restaurants' => $restaurants,
            'pager' => $this->restaurantModel->pager,
            'current_page' => $current_page,
            'validation' => \Config\Services::validation()

        ];
        return view('pages/edit_food', $data);
    }

    public function view_restaurant($id)
    {

        $current_page = $this->request->getVar('page_culture') ? $this->request->getVar('page_culture') : 1;

        // Ambil data restoran berdasarkan ID
        $restaurant = $this->restaurantModel->find($id);

        if ($restaurant && $restaurant['is_active'] == 'true') {
            // Jika restoran ditemukan dan is_active true, tampilkan data restoran
            $foods = $this->foodModel->getFood($id);

            $data = [
                'title' => 'Restoran',
                'foods' => $foods,
                'restaurants' => $restaurant,
                'pager' => $this->foodModel->pager,
                'current_page' => $current_page,
                'validation' => \Config\Services::validation()
            ];

            return view('pages/foods', $data);
        } else {
            // Jika restoran tidak ditemukan atau is_active false, berikan pesan dan redirect
            session()->setFlashdata('message', 'Restoran tidak tersedia atau tidak aktif');
            return redirect()->to(base_url('/restaurant'));
        }
    }


    public function comment_restaurant($id)
    {


        $current_page = $this->request->getVar('page_culture') ? $this->request->getVar('page_culture') : 1;
        $reviews = $this->reviewModel->getReview($id);
        $restaurants = $this->restaurantModel->getRestaurant($id);

        $data = [
            'title' => 'Restoran',
            'reviews' => $reviews,
            'restaurants' => $restaurants,
            'pager' => $this->reviewModel->pager,
            'current_page' => $current_page,
            'validation' => \Config\Services::validation()

        ];
        return view('pages/review', $data);
    }


    public function map()
    {

        $distanceModel = new DistanceModel();
        $distances = $distanceModel->getDistance(1);

        $data = [
            'title' => 'Peta',
            'distances' => $distances,
            'validation' => \Config\Services::validation()

        ];
        return view('pages/map', $data);
    }

    public function setting()
    {
        $applications = $this->applicationModel->find(1);
        $features = $this->featureModel->findAll();

        $data = [
            'title' => 'Pengaturan',
            'features' => $features,
            'applications' => $applications,
            'validation' => \Config\Services::validation()

        ];
        return view('pages/setting', $data);
    }

    public function api()
    {

        $distanceModel = new DistanceModel();
        $distances = $distanceModel->getDistance(1);

        $applications = $this->applicationModel->find(1);


        $features = $this->featureModel->findAll();

        $data = [
            'title' => 'API',
            'features' => $features,
            'distances' => $distances,
            'applications' => $applications,
            'validation' => \Config\Services::validation()

        ];
        return view('pages/api', $data);
    }

    public function broadcast()
    {

        $data = [
            'title' => 'Peta',
        ];
        return view('pages/broadcast', $data);
    }

    public function banner_save()
    {
        if (!$this->validate([
            'position_banner' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Posisi Banner Tidak Boleh Kosong',
                    'numeric' => 'Posisi Banner Harus Berupa Angka'

                ],

            ], 'url_image_banner' => [
                'rules' => 'uploaded[url_image_banner]|max_size[url_image_banner,2048]|is_image[url_image_banner]|mime_in[url_image_banner,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Pilih Foto Banner',
                    'max_size' => 'Ukuran foto terlalu besar (Maksimal 2Mb)',
                    'is_image' => 'Format file bukan foto',
                    'mime_in' => 'Format file bukan foto'
                ]
            ]


        ])) {
            session()->setFlashdata('message_error', 'Banner Gagal Ditambahkan');
            return redirect()->to(base_url() . '/banner')->withInput();
        }

        $fileImage = $this->request->getFile('url_image_banner');
        $fileImage->move('assets/banners');

        // ambil nama gambar
        $image_title = $fileImage->getName();

        $this->bannerModel->save([
            'position_banner' => $this->request->getVar('position_banner'),
            'url_image_banner' => $image_title,
        ]);

        session()->setFlashdata('message', 'Banner Berhasil Ditambahkan');
        return redirect()->to(base_url() . '/banner');
    }

    public function food_save()
    {

        $id_restaurant = $this->request->getVar('id_restaurant');

        if (!$this->validate([
            'food_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Makanan Tidak Boleh Kosong',

                ],

            ], 'food_price' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Harga Makanan Tidak Boleh Kosong',

                ],

            ], 'food_quantity' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jumlah Makanan Tidak Boleh Kosong',

                ],

            ], 'food_desc' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Deskripsi Makanan Tidak Boleh Kosong',

                ],

            ], 'food_image' => [
                'rules' => 'uploaded[food_image]|max_size[food_image,2048]|is_image[food_image]|mime_in[food_image,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Pilih Foto Makanan',
                    'max_size' => 'Ukuran foto terlalu besar (Maksimal 2Mb)',
                    'is_image' => 'Format file bukan foto',
                    'mime_in' => 'Format file bukan foto'
                ]
            ]


        ])) {
            session()->setFlashdata('message_error', 'Makanan Gagal Ditambahkan');

            return redirect()->to(base_url() . '/view_restaurant' . '/' . $id_restaurant)->withInput();
        }


        $fileImage = $this->request->getFile('food_image');
        $fileImage->move('assets/foods');

        // ambil nama gambar
        $image_title = $fileImage->getName();

        $this->foodModel->save([
            'id_restaurant' => $id_restaurant,
            'food_name' => $this->request->getVar('food_name'),
            'food_price' => $this->request->getVar('food_price'),
            'food_quantity' => $this->request->getVar('food_quantity'),
            'food_desc' => $this->request->getVar('food_desc'),
            'food_image' => $image_title,
        ]);

        session()->setFlashdata('message', 'Makanan Berhasil Ditambahkan');
        return redirect()->to(base_url() . '/view_restaurant' . '/' . $id_restaurant);
    }

    public function restaurant_save()
    {
        if (!$this->validate([
            'restaurant_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Restoran Tidak Boleh Kosong',
                ],

            ], 'restaurant_location' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Lokasi Restoran Tidak Boleh Kosong',
                ],

            ], 'open_restaurant' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam Buka Restoran Tidak Boleh Kosong',
                ],

            ], 'close_restaurant' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam Tutup Restoran Tidak Boleh Kosong',
                ],

            ], 'latitude_restaurant' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Latituded Restoran Tidak Boleh Kosong',
                ],

            ], 'longitude_restaurant' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Longitude Restoran Tidak Boleh Kosong',
                ],

            ], 'url_image_restaurant' => [
                'rules' => 'uploaded[url_image_restaurant]|max_size[url_image_restaurant,2048]|is_image[url_image_restaurant]|mime_in[url_image_restaurant,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Pilih Foto Restoran',
                    'max_size' => 'Ukuran foto terlalu besar (Maksimal 2Mb)',
                    'is_image' => 'Format file bukan foto',
                    'mime_in' => 'Format file bukan foto'
                ]
            ]


        ])) {
            session()->setFlashdata('message_error', 'Restoran Gagal Ditambahkan');
            return redirect()->to(base_url() . '/restaurant')->withInput();
        }

        $fileImage = $this->request->getFile('url_image_restaurant');
        $fileImage->move('assets/restaurants');

        // ambil nama gambar
        $image_title = $fileImage->getName();

        // Menyimpan data restoran dengan is_open sesuai kondisi
        $this->restaurantModel->save([
            'restaurant_name' => $this->request->getVar('restaurant_name'),
            'restaurant_location' => $this->request->getVar('restaurant_location'),
            'open_restaurant' => $this->request->getVar('open_restaurant'),
            'close_restaurant' => $this->request->getVar('close_restaurant'),
            'latitude_restaurant' => $this->request->getVar('latitude_restaurant'),
            'longitude_restaurant' => $this->request->getVar('longitude_restaurant'),
            'restaurant_rating' => 0,
            'restaurant_image' => $image_title,
            'is_open' => 'true',
            'is_active' => 'true',
        ]);

        session()->setFlashdata('message', 'Restoran Berhasil Ditambahkan');
        return redirect()->to(base_url() . '/restaurant');
    }

    public function is_open($id_restaurant)
    {
        $is_open = $this->request->getPost('is_open');

        // Perbarui status restaurant berdasarkan id_restaurant
        $this->restaurantModel->update($id_restaurant, ['is_open' => $is_open]);
    }

    public function banner_delete($id_banner)
    {
        // mengambil gambar
        $banner = $this->bannerModel->find($id_banner);

        // hapus gambar
        $path = 'assets/banners/' . $banner['url_image_banner'];

        // Memeriksa apakah file ada di path
        if (file_exists($path)) {
            // Jika ada, maka lakukan unlink
            unlink($path);
        }

        $this->bannerModel->delete($id_banner);
        session()->setFlashdata('message', 'Banner Berhasil Dihapus ');
        return redirect('banner');
    }

    public function food_delete($id_food)
    {

        d($id_food);
        // mengambil gambar
        $food = $this->foodModel->find($id_food);

        // hapus gambar
        $path = 'assets/foods/' . $food['food_image'];

        unlink($path);

        $this->foodModel->delete($id_food);
        session()->setFlashdata('message', 'Makanan Berhasil Dihapus ');
        return redirect()->to(base_url() . '/restaurant');
    }

    public function restaurant_delete($id_restaurant)
    {
        // mengambil gambar
        $restaurant = $this->restaurantModel->find($id_restaurant);

        // hapus gambar
        $path = 'assets/restaurants/' . $restaurant['restaurant_image'];

        // Memeriksa apakah file ada di path
        if (file_exists($path)) {
            // Jika ada, maka lakukan unlink
        }

        $this->restaurantModel->delete($id_restaurant);
        session()->setFlashdata('message', 'Restoran Berhasil Dihapus ');
        return redirect()->to(base_url() . '/restaurant');
    }

    public function user_delete($id_pengguna)
    {
        // mengambil gambar
        $user = $this->userModelApi->find($id_pengguna);

        // Membuat path gambar
        $path = 'assets/profile_photo/' . $user['gambar_pengguna'];

        // Memeriksa apakah file ada di path
        if (file_exists($path)) {
            // Jika ada, maka lakukan unlink
            unlink($path);
        }

        // Menghapus pengguna dari database
        $this->userModelApi->delete($id_pengguna);

        // Mengatur pesan flash untuk menampilkan pesan sukses
        session()->setFlashdata('message', 'Pengguna Berhasil Dihapus');

        // Redirect ke halaman pengguna
        return redirect()->to(base_url() . '/user');
    }

    public function driver_block($id_driver)
    {
        // Cari driver berdasarkan id_driver
        $driver = $this->driverModel->find($id_driver);

        // Jika driver ditemukan
        if ($driver) {
            // Update status menjadi 'block'
            $this->driverModel->update($id_driver, ['is_status' => 'block']);

            // Tampilkan pesan sukses
            session()->setFlashdata('message', 'Driver berhasil diblock');
        } else {
            // Tampilkan pesan error jika driver tidak ditemukan
            session()->setFlashdata('error', 'Driver tidak ditemukan');
        }

        // Redirect ke halaman yang sesuai, misalnya halaman utama driver
        return redirect()->to(base_url('/driver'));
    }

    public function driver_cancel($id_driver)
    {
        // Cari driver berdasarkan id_driver
        $driver = $this->driverModel->find($id_driver);

        // Jika driver ditemukan
        if ($driver) {
            // Update status menjadi 'cancel'
            $this->driverModel->update($id_driver, ['is_status' => 'cancel']);

            // Tampilkan pesan sukses
            session()->setFlashdata('message', 'Driver berhasil dicancel');
        } else {
            // Tampilkan pesan error jika driver tidak ditemukan
            session()->setFlashdata('error', 'Driver tidak ditemukan');
        }

        // Redirect ke halaman yang sesuai, misalnya halaman utama driver
        return redirect()->to(base_url('/driver'));
    }

    public function driver_accept($id_driver)
    {
        // Cari driver berdasarkan id_driver
        $driver = $this->driverModel->find($id_driver);

        // Jika driver ditemukan
        if ($driver) {
            // Update status menjadi 'accepted'
            $this->driverModel->update($id_driver, ['is_status' => 'accept']);

            // Tampilkan pesan sukses
            session()->setFlashdata('message', 'Driver berhasil diterima');
        } else {
            // Tampilkan pesan error jika driver tidak ditemukan
            session()->setFlashdata('error', 'Driver tidak ditemukan');
        }

        // Redirect ke halaman yang sesuai, misalnya halaman utama driver
        return redirect()->to(base_url('/driver'));
    }

    public function send_broadcast()
    {
        $curl = curl_init();

        $authKey        = application('key_message');
        $topic          = $this->request->getVar('topic');
        $title_message  = $this->request->getVar('title_message');
        $text_message   = $this->request->getVar('text_message');
        $image_message  = $this->request->getVar('image_message');

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
                    "to": "/topics/' . $topic . '",
                    "priority": "high",
                    "data" : {
                        "body" : "' . $text_message . '",
                        "title": "' . $title_message . '",
                        "image": "' . $image_message . '",
                        "type" : "common",
                        "key_2" : "Value for key_2"
                    }
                }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $authKey,
                "Content-Type: application/json",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            session()->setFlashdata('message', 'Notif Berhasil Dikirim ');
            return redirect()->to(base_url() . '/broadcast');
        }
    }

    public function update_account($id)
    {

        $type = $this->request->getVar('type');

        if ($type == "text") {

            $this->userModel->save([
                'id' => $id,
                'username' => $this->request->getVar('username'),
                'email' => $this->request->getVar('email'),
            ]);

            $this->applicationModel->save([
                'id_application' => $id,
                'app_name' => $this->request->getVar('app_name'),
            ]);

            session()->setFlashdata('message', 'Akun Berhasil Diubah');
        } else if ($type == "image") {
            $fileImage = $this->request->getFile('image');
            $old_image = $this->request->getVar('old_image');

            if ($fileImage->getError() == 4) {
                // Jika tidak ada file yang diunggah, maka gunakan nama gambar lama
                $image_title = $old_image;
            } else {
                // Generate nama file yang unik untuk menghindari nama file yang sama
                $newName = $fileImage->getRandomName();

                // Pindahkan file ke direktori yang ditentukan
                $fileImage->move('assets/image', $newName);

                // Hapus gambar lama
                if ($old_image && file_exists('assets/image/' . $old_image) && is_file('assets/image/' . $old_image)) {
                    unlink('assets/image/' . $old_image);
                }

                // Simpan nama gambar baru ke dalam database
                $this->userModel->update($id, ['image' => $newName]);

                // Set nama file baru sebagai judul gambar
                $image_title = $newName;

                $this->userModel->save([
                    'id' => $id,
                    'image' => $image_title,
                ]);
            }
            session()->setFlashdata('message', 'Akun Berhasil Diubah');
        } else {
            $this->applicationModel->save([
                'id_application' => $id,
                'admin_phone' => $this->request->getVar('admin_phone'),
            ]);
            session()->setFlashdata('message', 'Akun Berhasil Diubah');
        }

        // dd($this->request->getVar());
        return redirect()->to(base_url() . '/setting');
    }

    public function update_password($id)
    {

        if (!$this->validate([
            'password' => "required",
            'new_password' => "required",
            'confirm_password' => "required|matches[new_password]",
        ])) {
            session()->setFlashdata('message_error', 'Password Gagal Diubah');
            return redirect()->to('/setting')->withInput();
        }


        if (!password_verify(base64_encode(hash('sha384', $this->request->getVar('password'), true)), user()->password_hash)) {
            session()->setFlashdata('message_error', 'Password Lama Anda Salah');
            return redirect()->to(base_url() . '/setting');
        } else {
            $this->userModel->save([
                'id' => $id,
                'password_hash' => Password::hash($this->request->getVar('new_password')),
            ]);
            session()->setFlashdata('message', 'Password Berhasil Diubah');
            return redirect()->to(base_url() . '/setting');
        }
    }

    public function update_integrasi($id)
    {
        if (!$this->validate([
            'key_message' => "required",
        ])) {
            session()->setFlashdata('message_error', 'Key Auth Gagal Diubah');
            return redirect()->to('/setting')->withInput();
        }

        $this->applicationModel->update($id, ['key_message' => $this->request->getVar('key_message')]);

        session()->setFlashdata('message', 'Key Auth Berhasil Diubah');
        return redirect()->to(base_url() . '/setting');
    }

    public function update_fitur($id_fitur)
    {
        $this->featureModel->save([
            'id_fitur' => $id_fitur,
            'feature_status' => $this->request->getPost('feature_status'),
        ]);


        session()->setFlashdata('message', 'Akun Berhasil Diubah');
        return redirect()->to(base_url() . '/setting');
    }

    public function save_edit_restaurant()
    {


        if (!$this->validate([
            'restaurant_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Restoran Tidak Boleh Kosong',
                ],

            ], 'restaurant_location' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Lokasi Restoran Tidak Boleh Kosong',
                ],

            ], 'open_restaurant' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam Buka Restoran Tidak Boleh Kosong',
                ],

            ], 'close_restaurant' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam Tutup Restoran Tidak Boleh Kosong',
                ],

            ], 'latitude_restaurant' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Latituded Restoran Tidak Boleh Kosong',
                ],

            ], 'longitude_restaurant' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Longitude Restoran Tidak Boleh Kosong',
                ],

            ]


        ])) {
            session()->setFlashdata('message_error', 'Restoran Gagal Diubah');
            return redirect()->to(base_url() . '/restaurant/edit_restaurant' . '/' . $this->request->getVar('id_restaurant'))->withInput();
        }


        if ($this->request->getFile('url_image_restaurant')->getError() == 4) {
            $image_title = $this->request->getVar('old_image');
        } else {
            $fileImage = $this->request->getFile('url_image_restaurant');
            $fileImage->move('assets/restaurants');
            // ambil nama gambar
            $image_title = $fileImage->getName();

            unlink('assets/restaurants/' . $this->request->getVar('old_image'));
        }


        $this->restaurantModel->save([
            'id_restaurant' => $this->request->getVar('id_restaurant'),
            'restaurant_name' => $this->request->getVar('restaurant_name'),
            'restaurant_name' => $this->request->getVar('restaurant_name'),
            'restaurant_location' => $this->request->getVar('restaurant_location'),
            'open_restaurant' => $this->request->getVar('open_restaurant'),
            'close_restaurant' => $this->request->getVar('close_restaurant'),
            'latitude_restaurant' => $this->request->getVar('latitude_restaurant'),
            'longitude_restaurant' => $this->request->getVar('longitude_restaurant'),
            'restaurant_image' => $image_title,
        ]);

        session()->setFlashdata('message', 'Restoran Berhasil Diubah');
        return redirect()->to(base_url() . '/restaurant');
    }

    public function save_edit_food()
    {


        if (!$this->validate([
            'food_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Makanan Tidak Boleh Kosong',
                ],

            ], 'food_price' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Harga Makanan Tidak Boleh Kosong',
                ],

            ], 'food_quantity' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jumlah Makanan Tidak Boleh Kosong',
                ],

            ], 'food_desc' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Deskripsi Makanan Tidak Boleh Kosong',
                ],
            ]

        ])) {
            session()->setFlashdata('message_error', 'Makanan Gagal Diubah');
            return redirect()->to(base_url() . '/restaurant/edit_food' . '/' . $this->request->getVar('id_food'))->withInput();
        }


        if ($this->request->getFile('food_image')->getError() == 4) {
            $image_title = $this->request->getVar('old_image');
        } else {
            $fileImage = $this->request->getFile('food_image');
            $fileImage->move('assets/foods');
            // ambil nama gambar
            $image_title = $fileImage->getName();

            unlink('assets/foods/' . $this->request->getVar('old_image'));
        }


        $this->foodModel->save([
            'id_food' => $this->request->getVar('id_food'),
            'food_name' => $this->request->getVar('food_name'),
            'food_price' => $this->request->getVar('food_price'),
            'food_quantity' => $this->request->getVar('food_quantity'),
            'food_desc' => $this->request->getVar('food_desc'),
            'food_image' => $image_title,
        ]);

        session()->setFlashdata('message', 'Makanan Berhasil Diubah');
        return redirect()->to(base_url() . '/view_restaurant' . '/' . $this->request->getVar('id_restaurant'));
    }

    public function update_map()
    {

        $type = $this->request->getVar('type');

        if ($type == "price") {
            if (!$this->validate([
                '1km' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harga Tidak Boleh Kosong',
                    ],
                ], '2km' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harga Tidak Boleh Kosong',
                    ],

                ], '3km' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harga Tidak Boleh Kosong',
                    ],
                ], '4km' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harga Tidak Boleh Kosong',
                    ],
                ],

            ])) {
                session()->setFlashdata('message_error', 'Harga Gagal Diubah');
                return redirect()->to(base_url() . '/map')->withInput();
            }


            $this->distanceModel->save([
                'id' => $this->request->getVar('id'),
                '1km' => $this->request->getVar('1km'),
                '2km' => $this->request->getVar('2km'),
                '3km' => $this->request->getVar('3km'),
                '4km' => $this->request->getVar('4km'),
                'dua_koma_tujuh_km' => $this->request->getVar('dua_koma_tujuh_km'),
                'tiga_setengah_km' => $this->request->getVar('tiga_setengah_km'),
            ]);

            session()->setFlashdata('message', 'Harga Berhasil Diubah');
            return redirect()->to(base_url() . '/map');
        } else if ($type == "minimum") {


            if (!$this->validate([
                'minimum_balance' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Minimal Saldo Tidak Boleh Kosong',
                    ],

                ]


            ])) {
                session()->setFlashdata('message_error', 'Minimal Saldo Gagal Diubah');
                return redirect()->to(base_url() . '/map')->withInput();
            }


            $this->distanceModel->save([
                'id' => $this->request->getVar('id'),
                'minimum_balance' => $this->request->getVar('minimum_balance')
            ]);

            session()->setFlashdata('message', 'Minimal Saldo Berhasil Diubah');
            return redirect()->to(base_url() . '/map');
        } else {




            if (!$this->validate([
                'api_key_user' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Api Key Tidak Boleh Kosong',
                    ],

                ]


            ])) {
                session()->setFlashdata('message_error', 'Api Key Gagal Diubah');
                return redirect()->to(base_url() . '/map')->withInput();
            }

            $api_key = base64_encode($this->request->getVar('api_key_user'));

            $this->distanceModel->save([
                'id' => $this->request->getVar('id'),
                'api_key_user' => $api_key
            ]);

            session()->setFlashdata('message', 'Api Key Berhasil Diubah');
            return redirect()->to(base_url() . '/map');

            // AIzaSyDogEmVT_0VnpSRuMxN72wcv6w3qcofGgA
        }
    }

    public function update_balance()
    {
        $action = $this->request->getPost('action');
        $id_transaction = $this->request->getPost('id_transaction');
        $role = $this->request->getPost('role');

        // Ambil data transaksi dari model wallet
        $wallet = $this->walletModel->getWallet($id_transaction);
        $id_user = $wallet['id_user'];
        $balance = $wallet['balance'];

        // Tentukan model yang akan digunakan berdasarkan peran (role) pengguna
        $model = ($role == "driver") ? $this->driverModel : $this->userModelApi;
        $user = $model->find($id_user);

        // Periksa apakah pengguna ditemukan
        if (!$user) {
            session()->setFlashdata('message_error', 'Pengguna tidak ditemukan');
            return redirect()->to(base_url() . '/wallet');
        }

        // Update saldo pengguna berdasarkan peran (role)
        if ($role == "driver") {
            $reciveBalance = $user['balance_rider'] + $balance;
            $total = 'Rp. ' . number_format($balance, 0, ',', '.');
            $model->update($id_user, ['balance_rider' => $reciveBalance]);
        } else {
            $reciveBalance = $user['saldo_pengguna'] + $balance;
            $total = 'Rp. ' . number_format($balance, 0, ',', '.');
            $model->update($id_user, ['saldo_pengguna' => $reciveBalance]);
        }

        // Tandai transaksi sebagai sukses atau dibatalkan
        $status_payment = ($action == "accept") ? 'success' : 'canceled';
        $this->walletModel->save(['id_transaction' => $id_transaction, 'status_payment' => $status_payment]);

        // Kirim notifikasi
        $fcm_token = $user['fcm_token'];
        $notification_message = ($action == "accept") ? "Selamat, Saldo anda telah bertambah $total." : "Maaf, Top-Up Saldo Tidak Berhasil.";
        $this->sendNotification($fcm_token, ($action == "accept") ? "Top-Up Saldo Berhasil!" : "Top-Up Saldo Gagal!", $notification_message);

        // Set pesan flash sesuai dengan tindakan
        $flash_message = ($action == "accept") ? 'Saldo Berhasil Ditambah' : 'Top-Up Saldo Dibatalkan';
        session()->setFlashdata('message', $flash_message);

        return redirect()->to(base_url() . '/wallet');
    }

    private function sendNotification($fcm_token = null, $title = null, $body = null)
    {
        // Kirim notifikasi menggunakan cURL
        $authKey = application('key_message');
        dd($authKey);
        die;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "to" => $fcm_token,
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                    "sound" => "Default",
                    "priority" => "high"
                ]
            ]),
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $authKey,
                "Content-Type: application/json",
                "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
    }

    public function accept_mitra($id_mitra)
    {
        if ($id_mitra != null) {
            // Ambil data mitra berdasarkan id_mitra
            $mitra = $this->mitraModel->find($id_mitra);
            if ($mitra) {
                // Update status mitra menjadi 'success'
                $update_mitra = $this->mitraModel->update($id_mitra, ['status' => 'success']);

                if ($update_mitra) {
                    // Jika update pada tb_mitra berhasil, lakukan update pada tb_restaurant

                    // Ambil id_restaurant berdasarkan email mitra
                    $restaurant = $this->restaurantModel->where('user_email_mitra', $mitra['user_email_mitra'])->first();

                    if ($restaurant) {
                        // Perbarui is_active di tb_restaurant menjadi true
                        $update_restaurant = $this->restaurantModel->update($restaurant['id_restaurant'], ['is_active' => 'true']);

                        if ($update_restaurant) {
                            // Pesan sukses jika kedua update berhasil
                            session()->setFlashdata('message', 'Mitra berhasil diterima');
                        } else {
                            // Pesan kesalahan jika update tb_restaurant gagal
                            session()->setFlashdata('error', 'Gagal memperbarui status restaurant');
                        }
                    } else {
                        // Jika tidak ditemukan restaurant, berikan pesan kesalahan
                        session()->setFlashdata('error', 'Restaurant tidak ditemukan');
                    }
                } else {
                    // Pesan kesalahan jika update tb_mitra gagal
                    session()->setFlashdata('error', 'Gagal memperbarui status mitra');
                }
            } else {
                // Jika tidak ditemukan mitra, berikan pesan kesalahan
                session()->setFlashdata('error', 'Mitra tidak ditemukan');
            }

            // Redirect ke halaman mitra
            return redirect()->to(base_url() . '/mitra');
        } else {
            // Jika $id_mitra null, berikan pesan kesalahan
            session()->setFlashdata('error', 'ID Mitra tidak valid');
            return redirect()->to(base_url() . '/mitra');
        }
    }
}
