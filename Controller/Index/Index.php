<?php
namespace Mageplaza\ShareCart\Controller\Index;
use \Magento\Quote\Api\CartRepositoryInterface;


class Index extends \Magento\Framework\App\Action\Action
{
    protected $cartepository;
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        CartRepositoryInterface $cartRepository)
    {
        $this->_pageFactory = $pageFactory;
        $this->cartRepository = $cartRepository;
        return parent::__construct($context);
    }

    public function execute()
    {
//        die($this->getRequest()->getParam('quote_id'));
//        $quoteId= $this->getRequest()->getParam('quote_id');
        $quoteId=  base64_decode($this->getRequest()->getParam('quote_id'),true);

        $quote = $this->cartRepository->get($quoteId);

        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        /* @var $cart \Magento\Checkout\Model\Cart */
//        $cart = $this->_objectManager->get(\Magento\Checkout\Model\Cart::class);
        $items = $quote->getAllItems();

        $cart = $this->_objectManager->get(\Magento\Checkout\Model\Cart::class);
        foreach ($items as $item) {
            echo '<pre>'; print_r(json_decode(json_encode($item->getData())));
            echo '========';

//            try {
//                $cart->addOrderItem($item);
//            } catch (\Magento\Framework\Exception\LocalizedException $e) {
//                if ($this->_objectManager->get(\Magento\Checkout\Model\Session::class)->getUseNotice(true)) {
//                    $this->messageManager->addNotice($e->getMessage());
//                } else {
//                    $this->messageManager->addError($e->getMessage());
//                }
//                return $resultRedirect->setPath('*/*/history');
//            } catch (\Exception $e) {
//                $this->messageManager->addException($e, __('We can\'t add this item to your shopping cart right now.'));
//                return $resultRedirect->setPath('checkout/cart');
//            }
        }

        $cart->save();
        return $resultRedirect->setPath('checkout/cart');
    }
}