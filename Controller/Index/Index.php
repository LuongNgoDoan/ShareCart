<?php
namespace Mageplaza\ShareCart\Controller\Index;
use \Magento\Quote\Api\CartRepositoryInterface;


class Index extends \Magento\Framework\App\Action\Action
{
    protected $_productRepository;
    protected $cartepository;
    protected $_pageFactory;
    protected $cart;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        CartRepositoryInterface $cartRepository,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Model\ProductRepository $productRepository)
    {
        $this->_pageFactory = $pageFactory;
        $this->cartRepository = $cartRepository;
        $this->cart = $cart;
        $this->_productRepository = $productRepository;
        return parent::__construct($context);
    }

    public function execute()
    {
//        die($this->getRequest()->getParam('quote_id'));
//        $quoteId= $this->getRequest()->getParam('quote_id');
        $quoteId=  base64_decode($this->getRequest()->getParam('quote_id'),true);
//        die($quoteId);
        $quote = $this->cartRepository->get($quoteId);

        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        /* @var $cart \Magento\Checkout\Model\Cart */
//        $cart = $this->_objectManager->get(\Magento\Checkout\Model\Cart::class);
        $items = $quote->getItemsCollection();

        $cart = $this->_objectManager->get(\Magento\Checkout\Model\Cart::class);
        foreach ($items as $item) {

            if($item->getParentItem() ){
                for ($i=0;$i<$item->getParentItem()->getQty();$i++){
                    $this->cart->addProduct($item->getProduct(),$item->getQty());
                }
            }
            if(!$item->getParentItem() && !$item->getChildren() ){
                $this->cart->addProduct($item->getProduct(),$item->getQty());
            }
//            if($item->getChildren()){
//                echo '<pre>'. $item->getName().$item->getQty();
//            }else{
//                echo '<pre>'.'nochildren'. $item->getName().$item->getQty();
//            }

//            if($item->getChildren()){
//                echo '<pre>'. $item->getName().'==='.$item->getQty();
//                $this->cart->addProduct($item->getProduct(),$item->getQty());
//            }
//            echo '<pre>'. $item->getName().'==='.$item->getQty();


//            $this->cart->addProduct($item->getProduct(),$item->getQty());

        }
//        die;
        $cart->save();

        return $resultRedirect->setPath('checkout/cart');
    }
}